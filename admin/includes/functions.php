<?php
/**
 * Admin Helper Functions
 */

/**
 * Log an activity to the database
 *
 * @param mysqli $conn Database connection
 * @param int $admin_id ID of the admin performing the action
 * @param string $action Action type (create, update, delete, login, logout, etc.)
 * @param string $module Module name (products, orders, users, auth, system)
 * @param string $description Human-readable description of the action
 * @param string $record_type (Optional) Type of record affected (e.g., 'product', 'order')
 * @param int $record_id (Optional) ID of the record affected
 * @return bool True on success, False on failure
 */
function logActivity($conn, $admin_id, $action, $module, $description, $record_type = null, $record_id = null) {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    $sql = "INSERT INTO admin_activity_log 
            (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address, user_agent, request_uri) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issssisss", $admin_id, $action, $module, $description, $record_type, $record_id, $ip_address, $user_agent, $request_uri);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    return false;
}

/**
 * Triggers the recommendation engine retraining process asynchronously
 * 
 * @return bool True if request sent successfully, False otherwise
 */
function triggerRecommendationTraining() {
    $url = 'http://127.0.0.1:5000/api/train';
    $data = json_encode(['async' => true]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Fast timeout, fire and forget logic
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    // We expect a timeout (succesful fire-and-forget) or a quick success response
    if ($error && strpos($error, 'timed out') === false) {
        // Real error occurred
        return false;
    }
    
    return true;
}
?>
