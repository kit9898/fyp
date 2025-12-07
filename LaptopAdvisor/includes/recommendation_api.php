<?php
/**
 * PHP Client for LaptopAdvisor Recommendation Engine API
 * Handles communication between PHP and Python recommendation engine
 */

class RecommendationAPI {
    private $api_url;
    private $timeout;
    private $cache_enabled;
    private $cache_duration;
    
    public function __construct($host = '127.0.0.1', $port = 5000) {
        $this->api_url = "http://{$host}:{$port}/api";
        $this->timeout = 5; // 5 seconds timeout
        $this->cache_enabled = true;
        $this->cache_duration = 3600; // 1 hour cache
    }
    
    /**
     * Make HTTP request to the Python API
     */
    private function makeRequest($endpoint, $data = null, $method = 'POST') {
        $url = $this->api_url . $endpoint;
        
        $ch = curl_init($url);
        
        if ($method === 'POST' && $data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ));
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            error_log("Recommendation API Error: " . $error);
            return null;
        }
        
        if ($http_code !== 200) {
            error_log("Recommendation API HTTP Error: " . $http_code);
            return null;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Check if the API is healthy
     */
    public function healthCheck() {
        $response = $this->makeRequest('/health', null, 'GET');
        return $response !== null && isset($response['status']) && $response['status'] === 'healthy';
    }
    
    /**
     * Get personalized recommendations for a user
     * 
     * @param int $user_id User ID
     * @param string $use_case Optional use case filter
     * @param int $limit Number of recommendations to return
     * @return array|null Array of recommendations or null on error
     */
    public function getRecommendations($user_id, $use_case = null, $limit = 10) {
        // Check cache first
        if ($this->cache_enabled) {
            $cache_key = "ml_rec_{$user_id}_{$use_case}_{$limit}";
            $cached = $this->getFromCache($cache_key);
            if ($cached !== null) {
                return $cached;
            }
        }
        
        $data = [
            'user_id' => (int)$user_id,
            'limit' => (int)$limit
        ];
        
        if ($use_case !== null) {
            $data['use_case'] = $use_case;
        }
        
        $response = $this->makeRequest('/recommendations', $data);
        
        if ($response && isset($response['success']) && $response['success']) {
            $recommendations = $response['recommendations'];
            
            // Cache the result
            if ($this->cache_enabled) {
                $this->saveToCache($cache_key, $recommendations);
            }
            
            return $recommendations;
        }
        
        return null;
    }
    
    /**
     * Get similar products based on a product ID
     * 
     * @param int $product_id Product ID
     * @param int $limit Number of similar products to return
     * @return array|null Array of similar products or null on error
     */
    public function getSimilarProducts($product_id, $limit = 10) {
        // Check cache first
        if ($this->cache_enabled) {
            $cache_key = "ml_similar_{$product_id}_{$limit}";
            $cached = $this->getFromCache($cache_key);
            if ($cached !== null) {
                return $cached;
            }
        }
        
        $data = [
            'product_id' => (int)$product_id,
            'limit' => (int)$limit
        ];
        
        $response = $this->makeRequest('/similar-products', $data);
        
        if ($response && isset($response['success']) && $response['success']) {
            $similar_products = $response['similar_products'];
            
            // Cache the result
            if ($this->cache_enabled) {
                $this->saveToCache($cache_key, $similar_products);
            }
            
            return $similar_products;
        }
        
        return null;
    }
    
    /**
     * Get popular products for a use case
     * 
     * @param string $use_case Use case
     * @param int $limit Number of products to return
     * @return array|null Array of products or null on error
     */
    public function getPopularProducts($use_case, $limit = 10) {
        $data = [
            'use_case' => $use_case,
            'limit' => (int)$limit
        ];
        
        $response = $this->makeRequest('/popular', $data);
        
        if ($response && isset($response['success']) && $response['success']) {
            return $response['products'];
        }
        
        return null;
    }

    /**
     * Get AI-powered accessory recommendations
     * 
     * @param int $user_id User ID
     * @param int $laptop_id Laptop ID to base recommendations on
     * @param string $use_case Optional use case
     * @param int $limit Number of recommendations
     * @return array|null Array of recommendations
     */
    public function getAccessoryRecommendations($user_id, $laptop_id, $use_case = null, $limit = 6) {
        // Check cache
        if ($this->cache_enabled) {
            $cache_key = "ml_acc_{$user_id}_{$laptop_id}_{$use_case}_{$limit}";
            $cached = $this->getFromCache($cache_key);
            if ($cached !== null) {
                return $cached;
            }
        }

        $data = [
            'user_id' => (int)$user_id,
            'laptop_id' => (int)$laptop_id,
            'limit' => (int)$limit
        ];

        if ($use_case !== null) {
            $data['use_case'] = $use_case;
        }

        $response = $this->makeRequest('/accessory-recommendations', $data);

        if ($response && isset($response['success']) && $response['success']) {
            $recommendations = $response['recommendations'];
            
            if ($this->cache_enabled) {
                $this->saveToCache($cache_key, $recommendations);
            }
            
            return $recommendations;
        }

        return null;
    }

    /**
     * Get popular accessories by use case
     * 
     * @param string $use_case Use case
     * @param int $limit Number of accessories
     * @return array|null Array of accessories
     */
    public function getPopularAccessories($use_case, $limit = 6) {
        $data = [
            'use_case' => $use_case,
            'limit' => (int)$limit
        ];

        $response = $this->makeRequest('/popular-accessories', $data);

        if ($response && isset($response['success']) && $response['success']) {
            return $response['accessories'];
        }

        return null;
    }
    
    /**
     * Trigger model retraining
     * 
     * @param bool $async Run training in background
     * @return bool Success status
     */
    public function trainModel($async = true) {
        $data = ['async' => $async];
        $response = $this->makeRequest('/train', $data);
        
        return $response && isset($response['success']) && $response['success'];
    }
    
    /**
     * Get API statistics
     * 
     * @return array|null Statistics or null on error
     */
    public function getStats() {
        $response = $this->makeRequest('/stats', null, 'GET');
        return $response;
    }
    
    /**
     * Simple file-based caching
     */
    private function getFromCache($key) {
        $cache_file = sys_get_temp_dir() . '/laptopadvisor_cache_' . md5($key) . '.json';
        
        if (file_exists($cache_file)) {
            $cache_data = json_decode(file_get_contents($cache_file), true);
            
            if ($cache_data && isset($cache_data['expires']) && $cache_data['expires'] > time()) {
                return $cache_data['data'];
            }
            
            // Cache expired, delete it
            @unlink($cache_file);
        }
        
        return null;
    }
    
    private function saveToCache($key, $data) {
        $cache_file = sys_get_temp_dir() . '/laptopadvisor_cache_' . md5($key) . '.json';
        
        $cache_data = [
            'data' => $data,
            'expires' => time() + $this->cache_duration
        ];
        
        file_put_contents($cache_file, json_encode($cache_data));
    }
    
    /**
     * Clear all cached recommendations
     */
    public function clearCache() {
        $cache_dir = sys_get_temp_dir();
        $files = glob($cache_dir . '/laptopadvisor_cache_*.json');
        
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}

/**
 * Helper function to combine SQL and ML recommendations
 * 
 * @param array $sql_recommendations Recommendations from SQL
 * @param array $ml_recommendations Recommendations from ML
 * @param float $ml_weight Weight for ML recommendations (0-1)
 * @return array Combined and sorted recommendations
 */
function combineRecommendations($sql_recommendations, $ml_recommendations, $ml_weight = 0.6) {
    $combined = [];
    
    // Add SQL recommendations with weight
    foreach ($sql_recommendations as $rec) {
        $product_id = $rec['product_id'];
        $combined[$product_id] = [
            'product_id' => $product_id,
            'score' => (1 - $ml_weight) * ($rec['total_recommendation_score'] ?? 50) / 100,
            'data' => $rec
        ];
    }
    
    // Add ML recommendations with weight
    if ($ml_recommendations) {
        foreach ($ml_recommendations as $rec) {
            $product_id = $rec['product_id'];
            
            if (isset($combined[$product_id])) {
                // Product exists in both, combine scores
                $combined[$product_id]['score'] += $ml_weight * $rec['score'];
            } else {
                // New product from ML
                $combined[$product_id] = [
                    'product_id' => $product_id,
                    'score' => $ml_weight * $rec['score'],
                    'data' => null // Will need to fetch from DB
                ];
            }
        }
    }
    
    // Sort by combined score
    usort($combined, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    return $combined;
}
?>
