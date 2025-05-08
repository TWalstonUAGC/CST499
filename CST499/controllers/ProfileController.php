<?php
class ProfileController extends APIController {
    private $user;
    
    public function __construct() {
        parent::__construct();
        $this->loadUserProfile();
    }
    
    private function loadUserProfile() {
        $result = $this->db->executeSelectQuery("SELECT * FROM `User` WHERE UserId = " . $this->userId);
        
        if ($result->num_rows > 0) {
            $this->user = $result->fetch_assoc();
        } else {
            header('Location: login.php');
            exit;
        }
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function updateProfile($userData) {
        try {
            $this->db->executeSafeQuery("UPDATE `User` 
                SET Email = ?, FirstName = ?, LastName = ?, Address = ?, Phone = ?, SSN = ? 
                WHERE UserId = ?", $userData);
                
            return ['success' => true, 'message' => 'Profile updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to update profile: ' . $e->getMessage()];
        }
    }
    
    public function getEnrolledClasses() {
        $query = "SELECT Class.ClassId, Course.Name AS CourseName, Semester.Term, Semester.Year, Class.MaxEnrollment 
                  FROM Enrollment 
                  JOIN Class ON Enrollment.ClassId = Class.ClassId 
                  JOIN Course ON Class.CourseId = Course.CourseId 
                  JOIN Semester ON Class.SemesterId = Semester.SemesterId 
                  WHERE Enrollment.StudentId = ? 
                  ORDER BY Semester.SemesterId DESC";
                  
        return $this->db->executeSafeSelectQuery($query, [$this->userId]);
    }
    
    public function handleProfileUpdate() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['UserId'])) {
            $userId = htmlspecialchars($_POST['UserId'], ENT_QUOTES, 'UTF-8');
            $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
            $firstName = htmlspecialchars($_POST['FirstName'], ENT_QUOTES, 'UTF-8');
            $lastName = htmlspecialchars($_POST['LastName'], ENT_QUOTES, 'UTF-8');
            $address = htmlspecialchars($_POST['Address'], ENT_QUOTES, 'UTF-8');
            $phone = htmlspecialchars($_POST['Phone'], ENT_QUOTES, 'UTF-8');
            $ssn = htmlspecialchars($_POST['SSN'], ENT_QUOTES, 'UTF-8');

            $userData = [
                $email,
                $firstName,
                $lastName,
                $address,
                $phone,
                $ssn,
                $userId
            ];
            
            $result = $this->updateProfile($userData);
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                sendJsonResponse($result['success'], $result['message']);
            } else {
                header('Location: profile.php');
                exit;
            }
        }
    }
    
    public function handleUnenroll() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClassId'])) {
            $classIdToDelete = $_POST['deleteClassId'];
            
            try {
                $courseName = getCourseNameByClassId($this->db, $classIdToDelete);
                
                $deleteQuery = "DELETE FROM Enrollment WHERE StudentId = ? AND ClassId = ?";
                $this->db->executeSafeQuery($deleteQuery, [$this->userId, $classIdToDelete]);
                
                processWaitlist($this->db, $classIdToDelete);
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    sendJsonResponse(true, 'Successfully unenrolled from ' . $courseName . '.');
                } else {
                    header('Location: profile.php');
                    exit;
                }
            } catch (Exception $e) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    sendJsonResponse(false, 'Failed to unenroll: ' . $e->getMessage());
                } else {
                    echo "Error: " . $e->getMessage();
                }
            }
            exit;
        }
    }
}