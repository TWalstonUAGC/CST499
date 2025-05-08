<?php
class UsersController extends APIController {
    

    public function getAllUsers() {
        try {
            $query = "SELECT UserId, Email, FirstName, LastName, Address, Phone FROM `User`";
            $result = $this->db->executeSafeSelectQuery($query);
            error_log("Query executed successfully. Result count: " . count($result));
            return $result;
        } catch (Throwable $e) {
            error_log("Database error in getAllUsers: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            return [];
        }
    }
    

    public function getUserById($userId) {
        try {
            $query = "SELECT UserId, Email, FirstName, LastName, Address, Phone FROM `User` WHERE UserId = ?";
            $result = $this->db->executeSafeSelectQuery($query, [$userId]);
            return $result[0] ?? null;
        } catch (Throwable $e) {
            error_log("Database error in getUserById: " . $e->getMessage());
            return null;
        }
    }

    public function handleAPIRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['userId'])) {
                    $user = $this->getUserById($_GET['userId']);
                    if ($user) {
                        $this->success('User retrieved successfully', ['data' => ['user' => $user]]);
                    } else {
                        $this->error('User not found');
                    }
                } else {
                    $users = $this->getAllUsers();
                    error_log("Users fetched, preparing to send response with " . count($users) . " users");
                    $this->success('Users retrieved successfully', ['data' => ['users' => $users]]);
                }
            } else {
                $this->error('Invalid request method');
            }
        } catch (Throwable $e) {
            error_log("API error in handleAPIRequest: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            $this->error('Error processing request: ' . $e->getMessage());
        }
    }

    protected function requiresLogin() {
        return true;
    }
}