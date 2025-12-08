<?php
// This is the backend brain for the chatbot.
require_once 'includes/db_connect.php';
require_once 'includes/config.php';
require_once 'includes/ollama_client.php';

header('Content-Type: application/json');

// Handle JSON input
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_POST['action'] ?? $_GET['action'] ?? 'send_message';

switch ($action) {
    case 'send_message':
        handle_send_message($input);
        break;
    case 'get_history':
        handle_get_history($input);
        break;
    case 'start_session':
        handle_start_session();
        break;
    default:
        echo json_encode(['reply' => 'Invalid action.']);
}

function handle_start_session() {
    global $conn;
    $session_id = 'chat_' . uniqid() . '_' . bin2hex(random_bytes(8));
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_id = $_SESSION['user_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO conversations (session_id, user_id, user_ip, source, started_at) VALUES (?, ?, ?, 'web', NOW())");
    $stmt->bind_param("sis", $session_id, $user_id, $user_ip);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'session_id' => $session_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to create session']);
    }
}

function handle_send_message($input) {
    global $conn;
    
    $session_id = $input['session_id'] ?? null;
    $user_message = trim($input['message'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    if (empty($session_id)) {
        echo json_encode(['success' => false, 'error' => 'Missing session ID']);
        return;
    }

    if (empty($user_message)) {
        echo json_encode(['success' => false, 'error' => 'Empty message']);
        return;
    }

    // 0. LEAD CAPTURE: Check for Email
    $email_pattern = "/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i";
    if (preg_match($email_pattern, $user_message, $matches)) {
        $capturedEmail = $matches[0];
        // If we have a session ID, update email
        if (!empty($session_id)) {
            $stmt = $conn->prepare("UPDATE conversations SET customer_email = ? WHERE session_id = ?");
            $stmt->bind_param("ss", $capturedEmail, $session_id);
            $stmt->execute();
            // Append system note
            $user_message .= " [SYSTEM NOTE: User provided email: $capturedEmail]";
        }
    }

    // 1. Check/Create Conversation
    $stmt = $conn->prepare("SELECT conversation_id FROM conversations WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Create new conversation
        $stmt = $conn->prepare("INSERT INTO conversations (session_id, user_id, user_ip, source, started_at) VALUES (?, ?, ?, 'web', NOW())");
        $stmt->bind_param("sis", $session_id, $user_id, $user_ip);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Failed to create conversation']);
            return;
        }
        $conversation_id = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $conversation_id = $row['conversation_id'];
        
        // Update conversation stats
        $stmt = $conn->prepare("UPDATE conversations SET updated_at = NOW(), duration_seconds = TIMESTAMPDIFF(SECOND, started_at, NOW()), message_count = message_count + 1 WHERE conversation_id = ?");
        $stmt->bind_param("i", $conversation_id);
        $stmt->execute();
    }

    // 2. Store User Message
    $stmt = $conn->prepare("INSERT INTO conversation_messages (conversation_id, message_type, message_content, timestamp) VALUES (?, 'user', ?, NOW())");
    $stmt->bind_param("is", $conversation_id, $user_message);
    $stmt->execute();

    // 3. Generate Bot Response
    $bot_response = generate_bot_response($user_message);

    // 4. Store Bot Response
    $stmt = $conn->prepare("INSERT INTO conversation_messages (conversation_id, message_type, message_content, timestamp) VALUES (?, 'bot', ?, NOW())");
    $stmt->bind_param("is", $conversation_id, $bot_response);
    $stmt->execute();

    // 5. Send Response
    echo json_encode([
        'success' => true,
        'response' => $bot_response,
        'conversation_id' => $conversation_id
    ]);
}

function handle_get_history($input) {
    global $conn;
    $session_id = $input['session_id'] ?? '';
    
    if (empty($session_id)) {
        echo json_encode([]);
        return;
    }

    // Get conversation ID
    $stmt = $conn->prepare("SELECT conversation_id FROM conversations WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        echo json_encode([]);
        return;
    }
    
    $conversation_id = $res->fetch_assoc()['conversation_id'];

    $history = [];
    $stmt = $conn->prepare("SELECT message_type as sender, message_content as message FROM conversation_messages WHERE conversation_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $conversation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    
    echo json_encode($history);
}

function generate_bot_response($input_text) {
    global $conn;
    
    // Get context from global input if available (for currency/session)
    $raw_input = json_decode(file_get_contents('php://input'), true);
    $currency = $raw_input['currency'] ?? 'USD';
    $exchange_rate = floatval($raw_input['exchange_rate'] ?? 1.0);
    $currency_symbol = ($currency == 'MYR') ? 'RM' : (($currency == 'CNY') ? '¥' : '$');
    
    // Initialize Ollama Client
    $ollama = new OllamaClient(OLLAMA_API_URL, OLLAMA_MODEL, OLLAMA_TIMEOUT);
    
    // --- Step 1: Check for exact/strong intent matches in DB first (Fast Path) ---
    $stmt = $conn->prepare("
        SELECT ir.response_text 
        FROM training_phrases tp
        JOIN intents i ON tp.intent_id = i.intent_id
        JOIN intent_responses ir ON i.intent_id = ir.intent_id
        WHERE tp.phrase_text = ? AND i.is_active = 1 AND ir.is_active = 1
        LIMIT 1
    ");
    $stmt->bind_param("s", $input_text);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['response_text'];
    }
    
    // --- Step 2: Use Ollama to analyze intent ---
    $analysisPrompt = "Analyze this user message: \"$input_text\"\n" .
                     "Return a JSON object with:\n" .
                     "- type: 'product_search', 'general_chat', 'support'\n" .
                     "- search_criteria: (if product_search) object with 'category', 'budget', 'use_case', 'brand'\n" .
                     "- sentiment: 'positive', 'neutral', 'negative'";
    
    $analysis = $ollama->chat([
        ['role' => 'system', 'content' => 'You are a JSON-only analyzer. Output ONLY valid JSON.'],
        ['role' => 'user', 'content' => $analysisPrompt]
    ]);
    
    $intentData = json_decode($analysis['message'] ?? '{}', true);
    $sentiment = $intentData['sentiment'] ?? 'neutral';
    
    // --- Step 3: Handle Product Search & RAG ---
    // Always fetch products if potential intent, to give context
    $productContext = "";
    $foundProducts = [];
    
    // Enhanced Product Search Logic (Ported from api/chat_message.php with Currency Support)
    $criteria = $intentData['search_criteria'] ?? [];
    
    $sql = "SELECT * FROM products WHERE stock_quantity > 0";
    $params = [];
    $types = "";
    
    if (!empty($criteria['category'])) {
        $sql .= " AND (product_category LIKE ? OR related_to_category LIKE ?)";
        $cat = "%" . $criteria['category'] . "%";
        $params[] = $cat; $params[] = $cat; $types .= "ss";
    }
    
    if (!empty($criteria['brand'])) {
        $sql .= " AND brand LIKE ?";
        $brand = "%" . $criteria['brand'] . "%";
        $params[] = $brand; $types .= "s";
    }

    if (!empty($criteria['budget'])) {
        // Convert user budget to USD for DB query
        $budget_usd = floatval($criteria['budget']) / $exchange_rate;
        $sql .= " AND price <= ?";
        $params[] = $budget_usd; $types .= "d";
    }
    
    $sql .= " ORDER BY price ASC LIMIT 5";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $productsResult = $stmt->get_result();
    
    while ($p = $productsResult->fetch_assoc()) {
        $foundProducts[] = $p;
    }
    
    // Fetched Accessories for Cross-Selling
    if (!empty($foundProducts)) {
        $accSql = "SELECT * FROM products WHERE product_category IN ('mouse', 'keyboard', 'headset', 'bag') ORDER BY RAND() LIMIT 2";
        $accRes = $conn->query($accSql);
        while ($acc = $accRes->fetch_assoc()) {
            $foundProducts[] = $acc; // Add accessories to context
        }
    }

    if (!empty($foundProducts)) {
        $productContext = "=== AVAILABLE INVENTORY (Prices in $currency) ===\n";
        foreach ($foundProducts as $p) {
            // Convert Price
            $localPrice = $p['price'] * $exchange_rate;
            $formattedPrice = number_format($localPrice, 2);
            
            $productContext .= "- [{$p['product_category']}] **{$p['product_name']}** ({$p['brand']}): $currency_symbol$formattedPrice\n";
            $productContext .= "  Specs: {$p['cpu']}, {$p['ram_gb']}GB RAM. Use: {$p['primary_use_case']}.\n";
        }
        $productContext .= "=== END INVENTORY ===\n";
        $productContext .= "NOTE: Prices above are already converted to $currency. USE THEM EXACTLY.\n";
    }

    // --- Step 4: Construct Final System Prompt with Sales Logic ---
    $systemPrompt = SYSTEM_PROMPT; // From config.php (Should be the Sales one now)
    
    // Inject Hidden Sales Instructions
    $salesLogic = "\n\nCRITICAL INSTRUCTIONS:\n";
    $salesLogic .= "1. USER CURRENCY: User is shopping in **$currency**. ALL prices you mention MUST be in **$currency_symbol**. Do NOT mention USD.\n";
    $salesLogic .= "2. COMMISSION AGENT: You want to close the sale. Be persuasive.\n";
    $salesLogic .= "3. CROSS-SELL: If recommending a laptop, suggest a mouse/bag from the list.\n";
    $salesLogic .= "4. LEAD CAPTURE: If user likes a product, say: 'Would you like me to email you a formal quote (in $currency)? Please provide your email.'\n";
    
    $finalPrompt = $productContext . "\nUser Message: \"$input_text\"\n\nRespond to the user naturally using the inventory above.";

    // Call Ollama
    $response = $ollama->chat([
        ['role' => 'system', 'content' => $systemPrompt . $salesLogic],
        ['role' => 'user', 'content' => $finalPrompt]
    ]);
    
    return $response['message'] ?? "I'm having trouble connecting to my brain right now.";
}


function update_conversation_analysis($sentiment, $outcome) {
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);
    $session_id = $input['session_id'] ?? null;
    
    if ($session_id) {
        $stmt = $conn->prepare("UPDATE conversations SET sentiment = ?, outcome = ? WHERE session_id = ?");
        $stmt->bind_param("sss", $sentiment, $outcome, $session_id);
        $stmt->execute();
    }
}
?>