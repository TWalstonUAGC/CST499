<?php
class APIController {
    protected $db;
    protected $userId;
    protected $isLoggedIn;
    
    public function __construct() {
        // Use the initSession function from bootstrap instead of directly calling session_start
        if (!isset($_SESSION)) {
            initSession();
        }
        
        $this->db = new Database();
        $this->isLoggedIn = isset($_SESSION['userId']);
        $this->userId = $this->isLoggedIn ? $_SESSION['userId'] : null;
        
        // Check login for secured endpoints
        if ($this->requiresLogin() && !$this->isLoggedIn) {
            $this->sendJsonResponse(false, 'Authentication required');
        }
    }
    
    // Override this method in child classes that require login
    protected function requiresLogin() {
        return true; // Most API endpoints require login
    }
    
    // Common method to get semester and year from request
    protected function getFilters() {
        return [
            'semester' => isset($_POST['semester']) ? $_POST['semester'] : '',
            'year' => isset($_POST['year']) ? $_POST['year'] : ''
        ];
    }
    
    // Standard success response
    protected function success($message, $additionalData = []) {
        $this->sendJsonResponse(true, $message, $additionalData);
    }

    protected function error($message, $additionalData = []) {
        $this->sendJsonResponse(false, $message, $additionalData);
    }
    
    protected function sendJsonResponse($success, $message, $additionalData = []) {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message
        ], $additionalData));
        exit;
    }

  
    protected function buildUrlWithParams($baseUrl, $params = []) {
        if (empty($params)) {
            return $baseUrl;
        }
        
        $url = $baseUrl . '?';
        $first = true;
        
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                if (!$first) {
                    $url .= '&';
                }
                $url .= $key . '=' . urlencode($value);
                $first = false;
            }
        }
        
        return $url;
    }
}