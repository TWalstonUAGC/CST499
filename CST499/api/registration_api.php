<?php
require_once __DIR__ . '/../bootstrap.php';

class RegistrationController extends APIController {
    protected function requiresLogin() {
        return false;
    }
    
    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8');
            $lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8');
            $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
            $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
            $ssn = htmlspecialchars($_POST['ssn'], ENT_QUOTES, 'UTF-8');
            
            try {
                $checkEmailQuery = "SELECT COUNT(*) AS count FROM User WHERE Email = ?";
                $result = $this->db->executeSafeSelectQuery($checkEmailQuery, [$email]);
                
                if ($result[0]['count'] > 0) {
                    $this->error('Email address already registered');
                    return;
                }
                
                $insertQuery = "INSERT INTO User (Email, Password, FirstName, LastName, Address, Phone, SSN)
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $this->db->executeSafeQuery($insertQuery, [
                    $email, $password, $firstName, $lastName, $address, $phone, $ssn
                ]);
                
                $this->success('Registration successful', ['redirectTo' => 'login.php']);
            } catch (Exception $e) {
                $this->error('Registration failed: ' . $e->getMessage());
            }
        } else {
            $this->error('Invalid request method');
        }
    }
}

$controller = new RegistrationController();
$controller->handleRequest();