<?php
require_once __DIR__ . '/../bootstrap.php';

class UnenrollController extends APIController {
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClassId'])) {
            $classIdToDelete = $_POST['deleteClassId'];
            $filters = $this->getFilters();
            
            try {
                $classesController = new ClassesController();
                $courseName = $classesController->getCourseNameByClassId($classIdToDelete);
                
                $deleteQuery = "DELETE FROM Enrollment WHERE StudentId = ? AND ClassId = ?";
                $this->db->executeSafeQuery($deleteQuery, [$this->userId, $classIdToDelete]);
                
                $waitlistController = new WaitlistController();
                $waitlistController->processWaitlist($classIdToDelete);
                
                $this->success('Successfully unenrolled from ' . $courseName . '.', $filters);
            } catch (Exception $e) {
                $this->error('Failed to unenroll: ' . $e->getMessage());
            }
        } else {
            $this->error('Invalid request');
        }
    }
}

$controller = new UnenrollController();
$controller->handleRequest();