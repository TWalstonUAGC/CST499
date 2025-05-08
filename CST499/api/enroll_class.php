<?php
require_once __DIR__ . '/../bootstrap.php';

class EnrollController extends APIController {
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classId'])) {
            $classId = $_POST['classId'];
            $filters = $this->getFilters();
            
            $checkQuery = "SELECT COUNT(*) AS count FROM Enrollment WHERE ClassId = ? AND StudentId = ?";
            $result = $this->db->executeSafeSelectQuery($checkQuery, [$classId, $this->userId]);
            
            if ($result[0]['count'] > 0) {
                $this->error('You are already enrolled in this class.', $filters);
            }
            
            $capacityQuery = "SELECT MaxEnrollment, (SELECT COUNT(*) FROM Enrollment WHERE ClassId = ?) AS CurrentEnrollment FROM Class WHERE ClassId = ?";
            $capacityResult = $this->db->executeSafeSelectQuery($capacityQuery, [$classId, $classId]);
            
            if ($capacityResult[0]['CurrentEnrollment'] >= $capacityResult[0]['MaxEnrollment']) {
                $this->error('The class is full. Please join the waitlist.', array_merge($filters, ['redirectToWaitlist' => true]));
            }
            
            try {
                $courseName = getCourseNameByClassId($this->db, $classId);
                
                $insertQuery = "INSERT INTO Enrollment (ClassId, StudentId) VALUES (?, ?)";
                $this->db->executeSafeQuery($insertQuery, [$classId, $this->userId]);
                
                $this->success('Successfully enrolled in ' . $courseName . '.', $filters);
            } catch (Exception $e) {
                $this->error('Failed to enroll: ' . $e->getMessage(), $filters);
            }
        } else {
            $this->error('Invalid request', ['redirectTo' => 'error.php']);
        }
    }
}

$controller = new EnrollController();
$controller->handleRequest();