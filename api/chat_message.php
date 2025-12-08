<?php
/**
 * Chat Message API Endpoint
 * Handles user messages, retrieves conversation history, calls Ollama, and saves responses
 */

header('Content-Type: application/json');

require_once '../LaptopAdvisor/includes/db_connect.php';
require_once '../LaptopAdvisor/includes/config.php';
require_once '../LaptopAdvisor/includes/ollama_client.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($input['session_id']) || !isset($input['message'])) {
        throw new Exception('Missing required parameters: session_id and message');
    }
    
    $sessionId = trim($input['session_id']);
    $userMessage = trim($input['message']);
    
    // Validate message is not empty
    if (empty($userMessage)) {
        throw new Exception('Message cannot be empty');
    }

    // ========================================
    // LEAD CAPTURE: Check for Email
    // ========================================
    $email_pattern = "/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i";
    if (preg_match($email_pattern, $userMessage, $matches)) {
        $capturedEmail = $matches[0];
        
        // Update conversation with email
        $stmt = $conn->prepare("UPDATE conversations SET customer_email = ? WHERE session_id = ?");
        $stmt->bind_param("ss", $capturedEmail, $sessionId);
        $stmt->execute();
        $stmt->close();
        
        // Add a system note that email was captured
        $userMessage .= " [SYSTEM NOTE: User provided email: $capturedEmail]";
    }
    
    // Verify session exists and get conversation ID
    $stmt = $conn->prepare("SELECT conversation_id FROM conversations WHERE session_id = ?");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Invalid session ID');
    }
    
    $conversation = $result->fetch_assoc();
    $conversationId = $conversation['conversation_id'];
    $stmt->close();
    
    // Retrieve recent conversation history
    $stmt = $conn->prepare("SELECT message_type, message_content FROM conversation_messages WHERE conversation_id = ? ORDER BY timestamp DESC LIMIT ?");
    $historyLimit = CONVERSATION_HISTORY_LIMIT;
    $stmt->bind_param("ii", $conversationId, $historyLimit);
    $stmt->execute();
    $historyResult = $stmt->get_result();
    
    // Build messages array for Ollama (reverse order - oldest first)
    $messages = [];
    
    // Add system prompt
    $messages[] = [
        'role' => 'system',
        'content' => SYSTEM_PROMPT
    ];
    
    // Add conversation history (reverse the order)
    $historyMessages = [];
    while ($row = $historyResult->fetch_assoc()) {
        $historyMessages[] = [
            'role' => $row['message_type'] === 'user' ? 'user' : 'assistant',
            'content' => $row['message_content']
        ];
    }
    $stmt->close();
    
    // Reverse to get chronological order
    $messages = array_merge($messages, array_reverse($historyMessages));
    
    // ========================================
    // DYNAMIC COMMAND INJECTION
    // Retrieve active intents and inject as AI instructions
    // ========================================
    $intentSql = "SELECT intent_name, description, 
                  (SELECT response_text FROM intent_responses WHERE intent_id = i.intent_id AND is_default = 1 LIMIT 1) as default_response 
                  FROM intents i WHERE is_active = 1 ORDER BY priority DESC";
    $intentResult = $conn->query($intentSql);
    
    $dynamicRules = "";
    if ($intentResult && $intentResult->num_rows > 0) {
        $dynamicRules = "\n\n=== DYNAMIC COMMANDS ===\n";
        $dynamicRules .= "The following are specific instructions you MUST follow when users ask about these topics:\n\n";
        
        while($row = $intentResult->fetch_assoc()) {
            $intentName = $row['intent_name'];
            $description = $row['description'];
            $defaultResponse = $row['default_response'];
            
            // Build dynamic rule for this intent
            if (!empty($defaultResponse)) {
                $dynamicRules .= "- When user asks about '{$intentName}'";
                if (!empty($description)) {
                    $dynamicRules .= " ({$description})";
                }
                $dynamicRules .= ", you MUST include this information: \"{$defaultResponse}\"\n";
            }
        }
        
        $dynamicRules .= "\nThese commands have been configured by the administrator and take precedence over general knowledge.\n";
        $dynamicRules .= "=== END OF DYNAMIC COMMANDS ===\n";
    }
    
    // Inject dynamic rules into system prompt
    if (!empty($dynamicRules)) {
        $messages[0]['content'] = SYSTEM_PROMPT . $dynamicRules;
    }
    
    // ========================================
    // SALES AGENT PERSONALITY INJECTION
    // ========================================
    $salesLogic = "\n\nCRITICAL PERSONALITY INSTRUCTIONS:\n";
    $salesLogic .= "1. You are a COMMISSION-BASED SALES AGENT, not just a helper. You WANT to close the sale.\n";
    $salesLogic .= "2. CROSS-SELL: If you recommend a laptop, you MUST suggest a matching accessory (mouse/bag) from the inventory to increase the order value.\n";
    $salesLogic .= "3. CLOSE THE DEAL: If the user seems happy or says 'ok', 'good', 'thanks', or 'perfect', you MUST say: 'Would you like me to email you a formal quote to lock in this price? Please provide your email address.'\n";
    $salesLogic .= "4. Do not be annoying, but be PERSISTENT about the quote and the cross-sell.\n";
    
    // Append to system prompt
    $messages[0]['content'] .= $salesLogic;
    
    // ========================================
    // RAG: Fetch Relevant Products from Database
    // ========================================
    $productContext = fetchRelevantProducts($conn, $userMessage);
    
    // Inject product context into system message if products were found
    if (!empty($productContext)) {
        // Update the system message to include product catalog
        $messages[0]['content'] = SYSTEM_PROMPT . "\n\n" . $productContext;
    }
    
    // Add new user message with HIDDEN INSTRUCTION
    $hiddenInstruction = "";
    
    // Check if we should force a close
    $closingSignals = ['good', 'great', 'perfect', 'thanks', 'ok', 'price', 'buy', 'cart'];
    foreach ($closingSignals as $sig) {
        if (stripos($userMessage, $sig) !== false) {
             $hiddenInstruction .= " [SYSTEM: User is interested. ASK FOR EMAIL ADDRESS NOW.]";
             break;
        }
    }
    
    // Check if we should force cross-sell (if not already done)
    if (stripos($userMessage, 'laptop') !== false || stripos($userMessage, 'recommend') !== false) {
        $hiddenInstruction .= " [SYSTEM: Suggest an accessory (Mouse/Bag) with this laptop.]";
    }

    $messages[] = [
        'role' => 'user',
        'content' => $userMessage . $hiddenInstruction
    ];
    
    // Save user message to database
    $stmt = $conn->prepare("INSERT INTO conversation_messages (conversation_id, message_type, message_content, timestamp) VALUES (?, 'user', ?, NOW())");
    $stmt->bind_param("is", $conversationId, $userMessage);
    $stmt->execute();
    $userMessageId = $stmt->insert_id; // Capture ID for intent tracking
    $stmt->close();
    
    // ========================================
    // SENTIMENT ANALYSIS (LLM-as-a-Judge)
    // Analyze user message sentiment for analytics
    // ========================================
    $ollama = new OllamaClient(OLLAMA_API_URL, OLLAMA_MODEL, OLLAMA_TIMEOUT);

    if (strlen($userMessage) > 10) {
        $analysis = $ollama->analyzeSentiment($userMessage);
        
        if ($analysis && isset($analysis['sentiment']) && isset($analysis['score'])) {
            $stmt = $conn->prepare("UPDATE conversations SET sentiment = ?, satisfaction_rating = ? WHERE conversation_id = ?");
            $stmt->bind_param("sii", $analysis['sentiment'], $analysis['score'], $conversationId);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Call Ollama API
    $aiResponse = $ollama->chat($messages);
    
    if (!$aiResponse['success']) {
        // Save error to database as bot message
        $errorMessage = "I apologize, but I'm having trouble connecting to my AI service right now. Please try again in a moment. Error: " . $aiResponse['message'];
        
        $stmt = $conn->prepare("INSERT INTO conversation_messages (conversation_id, message_type, message_content, timestamp, response_time_ms) VALUES (?, 'bot', ?, NOW(), ?)");
        $stmt->bind_param("isi", $conversationId, $errorMessage, $aiResponse['response_time']);
        $stmt->execute();
        $stmt->close();
        
        throw new Exception($aiResponse['message']);
    }
    
    // Save bot response to database
    $botMessage = $aiResponse['message'];
    $responseTime = $aiResponse['response_time'];

    $stmt = $conn->prepare("INSERT INTO conversation_messages (conversation_id, message_type, message_content, timestamp, response_time_ms) VALUES (?, 'bot', ?, NOW(), ?)");
    $stmt->bind_param("isi", $conversationId, $botMessage, $responseTime);
    $stmt->execute();
    $stmt->close();
    
    // ========================================
    // INTENT USAGE TRACKING
    // Track which intents were used based on USER INPUT
    // ========================================
    error_log("Calling trackIntentByInput for message: $userMessage");
    trackIntentByInput($conn, $userMessage, $userMessageId);
    error_log("Finished trackIntentByInput");

    // Return success response to frontend
    echo json_encode([
        'success' => true,
        'response' => $botMessage,
        'response_time' => $responseTime,
        'conversation_id' => $conversationId
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();

/**
 * RAG Helper Function: Fetch Relevant Products
 * Retrieves products from database based on user query context
 */
function fetchRelevantProducts($conn, $userMessage) {
    // Extract budget if mentioned
    $budget = null;
    if (preg_match('/\$?\s*(\d{3,5})\s*(?:dollar|usd)?/i', $userMessage, $matches)) {
        $budget = (int)$matches[1];
    }
    
    // Detect use case keywords
    $useCase = null;
    $keywords = [
        'gaming' => ['gaming', 'game', 'gamer', 'play', 'rtx', 'gpu'],
        'creative' => ['creative', 'design', 'video', 'editing', 'photoshop', 'content', 'creator', 'render'],
        'business' => ['business', 'work', 'professional', 'office', 'productivity', 'excel', 'powerpoint'],
        'student' => ['student', 'school', 'study', 'education', 'homework', 'college', 'university']
    ];
    
    foreach ($keywords as $case => $wordList) {
        foreach ($wordList as $word) {
            if (stripos($userMessage, $word) !== false) {
                $useCase = $case;
                break 2;
            }
        }
    }
    
    // Check if user is asking about a specific product/brand
    $specificProduct = null;
    if (preg_match('/(macbook|apple|asus|hp|lenovo|dell|msi|thinkpad|zenbook|ideapad|spectre|victus|pavilion)/i', $userMessage, $matches)) {
        $specificProduct = $matches[1];
    }

    // Detect product category (laptop, mouse, keyboard, etc.)
    $category = null;
    $categories = ['laptop', 'mouse', 'keyboard', 'headset', 'monitor', 'bag', 'webcam'];
    foreach ($categories as $cat) {
        if (stripos($userMessage, $cat) !== false) {
            $category = $cat;
            break;
        }
    }
    
    // Default to 'laptop' if no specific category or product is mentioned, 
    // as this is primarily a Laptop Advisor
    if (!$category && !$specificProduct && stripos($userMessage, 'accessory') === false) {
        $category = 'laptop';
    }
    
    // Build SQL query
    $conditions = [];
    $params = [];
    $types = '';
    
    // If asking about specific brand/product, prioritize that
    if ($specificProduct) {
        $conditions[] = "(brand LIKE ? OR product_name LIKE ?)";
        $searchTerm = "%{$specificProduct}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    if ($budget) {
        $conditions[] = "price <= ?";
        $params[] = $budget;
        $types .= 'd';
    }
    
    if ($useCase && !$specificProduct) {
        // Only filter by use case if not asking about specific product
        $conditions[] = "primary_use_case = ?";
        $params[] = $useCase;
        $types .= 's';
    }

    if ($category) {
        $conditions[] = "product_category = ?";
        $params[] = $category;
        $types .= 's';
    }
    
    $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    
    // Fetch products
    $query = "SELECT product_id, product_name, brand, price, cpu, gpu, ram_gb, storage_gb, 
              storage_type, display_size, description, primary_use_case, product_category 
              FROM products 
              {$whereClause}
              ORDER BY price ASC 
              LIMIT 8";
    
    if (!empty($params)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // No filters - return all products (general inquiry)
        $result = $conn->query($query);
    }
    
    if ($result->num_rows === 0) {
        return ''; // No products found, AI will respond generally
    }
    
    // Format products for AI context
    $productList = "\n\n=== AVAILABLE INVENTORY ===\n";
    $productList .= "IMPORTANT: You MUST ONLY recommend products from this list.\n";
    
    $foundLaptop = false;
    $count = 0;
    while ($product = $result->fetch_assoc()) {
        $count++;
        if ($product['product_category'] == 'laptop') $foundLaptop = true;
        
        $productList .= "{$count}. [{$product['product_category']}] **{$product['brand']} {$product['product_name']}** - \${$product['price']}\n";
        $productList .= "   Specs: {$product['cpu']}, " . ($product['gpu'] ?? 'Integrated') . ", {$product['ram_gb']}GB RAM\n";
        $productList .= "   Best For: " . ucfirst($product['primary_use_case']) . "\n";
        $productList .= "\n";
    }
    
    // ========================================
    // CROSS-SELLING: Fetch Accessories
    // ========================================
    if ($foundLaptop) {
        $accessoryQuery = "SELECT product_name, brand, price, product_category 
                           FROM products 
                           WHERE product_category IN ('mouse', 'keyboard', 'headset', 'bag') 
                           ORDER BY rand() LIMIT 2";
        $accResult = $conn->query($accessoryQuery);
        
        if ($accResult->num_rows > 0) {
            $productList .= "--- SUGGESTED ADD-ONS (CROSS-SELL) ---\n";
            while ($acc = $accResult->fetch_assoc()) {
                 $productList .= "+ [Accessory] **{$acc['brand']} {$acc['product_name']}** - \${$acc['price']} ({$acc['product_category']})\n";
            }
            $productList .= "--------------------------------------\n";
            $productList .= "INSTRUCTION: If you recommend a laptop, ALSO suggest 1-2 add-ons from above to increase value.\n";
        }
    }
    
    $productList .= "=== END OF INVENTORY ===\n";
    $productList .= "REMINDER: You are a sales agent. Cross-sell accessories and ask for the email to send a quote!\n";
    
    if (isset($stmt)) {
        $stmt->close();
    }
    
    return $productList;
}

/**
 * Intent Tracking Function
 * Detects which intent was used based on USER INPUT matching Training Phrases
 * Updates usage statistics automatically
 */
function trackIntentByInput($conn, $userInput, $userMessageId) {
    error_log("trackIntentByInput started for: $userInput");
    // Get all active intents with their training phrases
    $query = "SELECT t.phrase_text, i.intent_id, i.intent_name 
              FROM training_phrases t 
              JOIN intents i ON t.intent_id = i.intent_id 
              WHERE i.is_active = 1";
    
    $result = $conn->query($query);
    
    if (!$result) {
        error_log("trackIntentByInput query failed: " . $conn->error);
        return; // Query failed, skip tracking
    }
    
    $bestMatchIntentId = null;
    $bestMatchIntentName = null;
    $highestSimilarity = 0;

    while ($row = $result->fetch_assoc()) {
        $phrase = $row['phrase_text'];
        
        // 1. Check for direct containment (stripos) - Strongest Match
        if (stripos($userInput, $phrase) !== false) {
            $bestMatchIntentId = $row['intent_id'];
            $bestMatchIntentName = $row['intent_name'];
            $highestSimilarity = 100;
            error_log("Exact match found: $phrase (Intent: $bestMatchIntentName)");
            break; // Stop immediately on exact phrase match
        }
        
        // 2. Check similarity
        similar_text(strtolower($userInput), strtolower($phrase), $percent);
        if ($percent > $highestSimilarity) {
            $highestSimilarity = $percent;
            $bestMatchIntentId = $row['intent_id'];
            $bestMatchIntentName = $row['intent_name'];
        }
    }

    error_log("Best match: " . ($bestMatchIntentName ?? 'None') . " ($highestSimilarity%)");

    // Threshold for fuzzy match (e.g., > 60% similarity)
    if ($bestMatchIntentId && $highestSimilarity > 60) {
        // Intent was used! Update statistics
        $stmt = $conn->prepare("UPDATE intents 
                                SET usage_count = usage_count + 1,
                                    success_count = success_count + 1,
                                    last_used_at = NOW()
                                WHERE intent_id = ?");
        $stmt->bind_param("i", $bestMatchIntentId);
        $stmt->execute();
        $stmt->close();
        error_log("Updated usage count for Intent ID: $bestMatchIntentId");

        // Update conversation message with detected intent and confidence
        $confidence = $highestSimilarity / 100;
        $stmt = $conn->prepare("UPDATE conversation_messages 
                                SET intent_detected = ?, 
                                    intent_confidence = ? 
                                WHERE message_id = ?");
        $stmt->bind_param("sdi", $bestMatchIntentName, $confidence, $userMessageId);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("No intent matched above threshold.");
        // No intent detected (Unrecognized)
        $stmt = $conn->prepare("UPDATE conversation_messages 
                                SET intent_detected = NULL, 
                                    intent_confidence = 0 
                                WHERE message_id = ?");
        $stmt->bind_param("i", $userMessageId);
        $stmt->execute();
        $stmt->close();
    }
}
?>
