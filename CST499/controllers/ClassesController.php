<?php
class ClassesController extends APIController {
    
    public function getSemesters() {
        $query = "SELECT DISTINCT Term FROM Semester";
        return $this->db->executeSelectQuery($query);
    }
    
    public function getYears() {
        $query = "SELECT DISTINCT Year FROM Semester";
        return $this->db->executeSelectQuery($query);
    }
    
    public function getClasses($selectedSemester = '', $selectedYear = '') {
        if (empty($selectedSemester) && empty($selectedYear)) {
            $query = "SELECT Class.ClassId, Course.Name AS CourseName, Semester.Term, Semester.Year, Class.MaxEnrollment, Course.Description, 
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.ClassId = Class.ClassId) AS CurrentEnrollment, 
                    (SELECT COUNT(*) FROM Enrollment e JOIN Class c ON e.ClassId = c.ClassId WHERE e.StudentId = ? AND c.SemesterId = Class.SemesterId) AS ClassesInSemester, 
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.ClassId = Class.ClassId AND Enrollment.StudentId = ?) AS AlreadyEnrolled 
                    FROM Class 
                    JOIN Course ON Class.CourseId = Course.CourseId 
                    JOIN Semester ON Class.SemesterId = Semester.SemesterId";
            return $this->db->executeSafeSelectQuery($query, [$this->userId, $this->userId]);
        } else {
            $query = "SELECT Class.ClassId, Course.Name AS CourseName, Semester.Term, Semester.Year, Class.MaxEnrollment, Course.Description, 
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.ClassId = Class.ClassId) AS CurrentEnrollment, 
                    (SELECT COUNT(*) FROM Enrollment e JOIN Class c ON e.ClassId = c.ClassId WHERE e.StudentId = ? AND c.SemesterId = Class.SemesterId) AS ClassesInSemester, 
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.ClassId = Class.ClassId AND Enrollment.StudentId = ?) AS AlreadyEnrolled 
                    FROM Class 
                    JOIN Course ON Class.CourseId = Course.CourseId 
                    JOIN Semester ON Class.SemesterId = Semester.SemesterId 
                    WHERE (? = '' OR Semester.Term = ?) AND (? = '' OR Semester.Year = ?)";
            return $this->db->executeSafeSelectQuery($query, [$this->userId, $this->userId, $selectedSemester, $selectedSemester, $selectedYear, $selectedYear]);
        }
    }
    
    public function handleAPIRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $selectedSemester = $_GET['semester'] ?? '';
            $selectedYear = $_GET['year'] ?? '';
            
            $classes = $this->getClasses($selectedSemester, $selectedYear);
            $this->success('Classes retrieved successfully', [
                'classes' => $classes,
                'semester' => $selectedSemester,
                'year' => $selectedYear
            ]);
        } else {
            $this->error('Invalid request method');
        }
    }
    
    public function getCourseNameByClassId($classId) {
        $courseNameQuery = "SELECT Course.Name FROM Class 
                        JOIN Course ON Class.CourseId = Course.CourseId 
                        WHERE Class.ClassId = ?";
        $courseNameResult = $this->db->executeSafeSelectQuery($courseNameQuery, [$classId]);
        return !empty($courseNameResult) ? $courseNameResult[0]['Name'] : 'the class';
    }
}