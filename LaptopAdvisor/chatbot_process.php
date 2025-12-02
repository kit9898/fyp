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
        $stmt = $conn->prepare("UPDATE conversations SET updated_at = NOW(), message_count = message_count + 1 WHERE conversation_id = ?");
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

function generate_bot_response($input) {
    global $conn;
    
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
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['response_text'];
    }
    
    // --- Step 2: Use Ollama to analyze intent and extract entities ---
    $analysisPrompt = "Analyze this user message: \"$input\"\n" .
                     "Return a JSON object with:\n" .
                     "- type: 'product_search', 'general_chat', 'support', or 'greeting'\n" .
                     "- search_criteria: (if product_search) object with 'category' (laptop, mouse, etc), 'budget' (number), 'use_case' (gaming, business, student, creative), 'brand'\n" .
                     "- sentiment: 'positive', 'neutral', 'negative'";
    
    $analysis = $ollama->chat([
        ['role' => 'system', 'content' => 'You are a JSON-only analyzer. Output ONLY valid JSON.'],
        ['role' => 'user', 'content' => $analysisPrompt]
    ]);
    
    $intentData = json_decode($analysis['message'] ?? '{}', true);
    
    // Extract Sentiment and Update Conversation
    $sentiment = $intentData['sentiment'] ?? 'neutral';
    $outcome = 'in_progress';
    
    // --- Step 3: Handle Product Search ---
    if (isset($intentData['type']) && $intentData['type'] === 'product_search') {
        $outcome = 'product_recommendation';
        $criteria = $intentData['search_criteria'] ?? [];
        
        // Build dynamic SQL query
        $sql = "SELECT * FROM products WHERE stock_quantity > 0";
        $params = [];
        $types = "";
        
        if (!empty($criteria['category'])) {
            $sql .= " AND (product_category LIKE ? OR related_to_category LIKE ?)";
            $cat = "%" . $criteria['category'] . "%";
            $params[] = $cat;
            $params[] = $cat;
            $types .= "ss";
        }
        
        if (!empty($criteria['brand'])) {
            $sql .= " AND brand LIKE ?";
            $brand = "%" . $criteria['brand'] . "%";
            $params[] = $brand;
            $types .= "s";
        }
        
        if (!empty($criteria['use_case'])) {
            $sql .= " AND (primary_use_case LIKE ? OR related_to_category LIKE ?)";
            $use = "%" . $criteria['use_case'] . "%";
            $params[] = $use;
            $params[] = $use;
            $types .= "ss";
        }
        
        if (!empty($criteria['budget'])) {
            $sql .= " AND price <= ?";
            $params[] = $criteria['budget'];
            $types .= "d";
        }
        
        $sql .= " ORDER BY price ASC LIMIT 3";
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $productsResult = $stmt->get_result();
        
        $foundProducts = [];
        while ($p = $productsResult->fetch_assoc()) {
            $foundProducts[] = $p;
        }
        
        // Generate response with product data
        if (!empty($foundProducts)) {
            $productContext = "Found these products:\n";
            foreach ($foundProducts as $p) {
                $productContext .= "- {$p['product_name']} ({$p['brand']}): \${$p['price']}. Specs: {$p['cpu']}, {$p['gpu']}, {$p['ram_gb']}GB RAM. Best for: {$p['primary_use_case']}.\n";
            }
            
            $finalPrompt = "User asked: \"$input\"\n\n" .
                          "Context: We have these products in stock:\n$productContext\n\n" .
                          "Task: Recommend these products to the user naturally. Highlight why they fit their request. Keep it concise.";
            
            $response = $ollama->chat([
                ['role' => 'system', 'content' => SYSTEM_PROMPT],
                ['role' => 'user', 'content' => $finalPrompt]
            ]);
            
            update_conversation_analysis($sentiment, $outcome);
            
            return $response['message'] ?? "I found some great options for you! Check out the " . $foundProducts[0]['product_name'] . ".";
        } else {
            // No exact matches, ask Ollama to handle gracefully
            $finalPrompt = "User asked: \"$input\"\n\n" .
                          "Context: I searched our inventory but found no exact matches for their specific criteria.\n" .
                          "Task: Apologize politely and suggest they check our full catalog or adjust their budget/criteria.";
            
            $response = $ollama->chat([
                ['role' => 'system', 'content' => SYSTEM_PROMPT],
                ['role' => 'user', 'content' => $finalPrompt]
            ]);
            
            update_conversation_analysis($sentiment, 'no_results');
            
            return $response['message'];
        }
    }
    
    // --- Step 4: Handle General Chat / Other Intents ---
    $response = $ollama->chat([
        ['role' => 'system', 'content' => SYSTEM_PROMPT],
        ['role' => 'user', 'content' => $input]
    ]);
    
    update_conversation_analysis($sentiment, 'general_chat');
    
    return $response['message'] ?? "I'm having trouble connecting to my brain right now. Please try again later.";
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