<?php
require_once 'bootstrap.php';

// Instantiate the controller
$controller = new ClassesController();

// Get data from controller
$semesters = $controller->getSemesters();
$years = $controller->getYears();

$selectedSemester = '';
$selectedYear = '';

if (isset($_POST['semester'])) {
    $selectedSemester = $_POST['semester'];
} 
elseif (isset($_GET['semester'])) {
    $selectedSemester = $_GET['semester'];
}

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
} 
elseif (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];
}

$result = $controller->getClasses($selectedSemester, $selectedYear);


$isLoggedIn = isset($_SESSION['userId']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Classes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>
<?php require 'master.php';?>
<div class="container mt-5">
    <h1 class="mb-4">Classes Available</h1>

    <form method="post" action="" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="semester" class="form-label">Semester</label>
                <select name="semester" id="semester" class="form-select">
                    <option value="">All</option>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo $semester['Term']; ?>" <?php echo $selectedSemester === $semester['Term'] ? 'selected' : ''; ?>>
                            <?php echo $semester['Term']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="year" class="form-label">Year</label>
                <select name="year" id="year" class="form-select">
                    <option value="">All</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year['Year']; ?>" <?php echo $selectedYear === $year['Year'] ? 'selected' : ''; ?>>
                            <?php echo $year['Year']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <?php if (!empty($selectedSemester) || !empty($selectedYear)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Course Name</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Max Enrollment</th>
                    <th>Current Enrollment</th>
                    <?php if ($isLoggedIn): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($result)): ?>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?php echo $row['ClassId']; ?></td>
                            <td><span title="<?php echo $row['Description']; ?>"><?php echo $row['CourseName']; ?></span></td>
                            <td><?php echo $row['Term']; ?></td>
                            <td><?php echo $row['Year']; ?></td>
                            <td><?php echo $row['MaxEnrollment']; ?></td>
                            <td><?php echo $row['CurrentEnrollment']; ?></td>
                            <?php if ($isLoggedIn): ?>
                                <td>
                                    <?php if ($row['AlreadyEnrolled'] > 0): ?>
                                        <form method="post" data-ajax="api/unenroll_api.php">
                                            <input type="hidden" name="deleteClassId" value="<?php echo $row['ClassId']; ?>">
                                            <input type="hidden" name="semester" value="<?php echo $selectedSemester; ?>">
                                            <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
                                            <button type="submit" class="btn btn-danger">Unenroll</button>
                                        </form>
                                    <?php elseif ($row['ClassesInSemester'] >= 3): ?>
                                        <button class="btn btn-secondary" disabled>Max Classes Reached</button>
                                    <?php elseif ($row['CurrentEnrollment'] >= $row['MaxEnrollment']): ?>
                                        <form method="post" data-ajax="api/add_waitlist.php">
                                            <input type="hidden" name="classId" value="<?php echo $row['ClassId']; ?>">
                                            <input type="hidden" name="semester" value="<?php echo $selectedSemester; ?>">
                                            <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
                                            <button type="submit" class="btn btn-warning">Join Waitlist</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" data-ajax="api/enroll_class.php">
                                            <input type="hidden" name="classId" value="<?php echo $row['ClassId']; ?>">
                                            <input type="hidden" name="semester" value="<?php echo $selectedSemester; ?>">
                                            <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
                                            <button type="submit" class="btn btn-primary">Register</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo $isLoggedIn ? 7 : 6; ?>" class="text-center">No classes found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require_once 'footer.php';?>
</body>
</html>