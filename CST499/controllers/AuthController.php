<?php
class AuthController extends APIController {
    
    // Override the requiresLogin method since auth pages are accessible without login
    protected function requiresLogin() {
        return false;
    }
    
    /**
     * Attempt to authenticate a user
     */
    public function authenticate($username, $password) {
        try {
            $result = $this->db->executeSafeSelectQuery(
                "SELECT * FROM `User` WHERE email = ? AND password = ?", 
                [$username, $password]
            );
            
            if (!empty($result)) {
                $user = $result[0];
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $user['UserId'];
                
                return [
                    'success' => true, 
                    'message' => 'Login successful',
                    'redirectTo' => 'index.php'
                ];
            } else {
                return [
                    'success' => false, 
                    'message' => 'Invalid username or password',
                    'redirectTo' => 'error.php'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false, 
                'message' => 'Login failed: ' . $e->getMessage()
            ];
        }
    }
    
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $result = $this->authenticate($username, $password);
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->sendJsonResponse($result['success'], $result['message'], 
                    ['redirectTo' => $result['redirectTo'] ?? null]
                );
            } else {
                if ($result['success']) {
                    header('Location: ' . ($result['redirectTo'] ?? 'index.php'));
                } else {
                    header('Location: error.php');
                }
                exit();
            }
        }
    }
    public function handleLogout() {
        session_unset();
        session_destroy();
        
        // Check if it's an AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->sendJsonResponse(true, 'Logout successful', ['redirectTo' => 'login.php']);
        } else {
            header('Location: login.php');
            exit();
        }
    }
}