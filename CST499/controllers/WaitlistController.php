<?php
class WaitlistController extends APIController {
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classId'])) {
            $classId = $_POST['classId'];
            $filters = $this->getFilters();
            
            // Get course name - now using ClassesController
            $classesController = new ClassesController();
            $courseName = $classesController->getCourseNameByClassId($classId);
            
            // Check if user is already on the waitlist
            $checkQuery = "SELECT COUNT(*) AS count FROM WaitList WHERE ClassId = ? AND StudentId = ?";
            $result = $this->db->executeSafeSelectQuery($checkQuery, [$classId, $this->userId]);
            
            if ($result[0]['count'] > 0) {
                $this->error('You are already on the waitlist for ' . $courseName . '.', $filters);
            }
            
            // Get the next sequence number
            $sequenceQuery = "SELECT IFNULL(MAX(Sequence), 0) + 1 AS nextSequence FROM WaitList WHERE ClassId = ?";
            $sequenceResult = $this->db->executeSafeSelectQuery($sequenceQuery, [$classId]);
            $nextSequence = $sequenceResult[0]['nextSequence'];
            
            try {
                // Add user to waitlist
                $insertQuery = "INSERT INTO WaitList (ClassId, StudentId, Sequence) VALUES (?, ?, ?)";
                $this->db->executeSafeQuery($insertQuery, [$classId, $this->userId, $nextSequence]);
                
                // Return success response
                $this->success('Successfully added to the waitlist for ' . $courseName . '.', $filters);
            } catch (Exception $e) {
                $this->error('Failed to join waitlist: ' . $e->getMessage(), $filters);
            }
        } else {
            $this->error('Invalid request', ['redirectTo' => 'error.php']);
        }
    }
    
    /**
     * Handle waitlist operations when a student unenrolls
     * Moved from utils.php
     * 
     * @param int $classId The class ID to process waitlist for
     * @return int|null The ID of the next user to enroll or null if no waitlist
     */
    public function processWaitlist($classId) {
        $waitlistQuery = "SELECT StudentId FROM WaitList WHERE ClassId = ? ORDER BY Sequence ASC LIMIT 1";
        $waitlistResult = $this->db->executeSafeSelectQuery($waitlistQuery, [$classId]);

        if (!empty($waitlistResult)) {
            $nextUser = $waitlistResult[0]['StudentId'];
            
            $removeWaitlistQuery = "DELETE FROM WaitList WHERE ClassId = ? AND StudentId = ?";
            $this->db->executeSafeQuery($removeWaitlistQuery, [$classId, $nextUser]);
            
            $this->notifyUserAboutEnrollmentOpportunity($nextUser, $classId);
            
            return $nextUser;
        }
        
        return null;
    }


    protected function notifyUserAboutEnrollmentOpportunity($userId, $classId) {
        // Code to notify user would go here
        // Could use email, SMS, or in-app notification
    }
}