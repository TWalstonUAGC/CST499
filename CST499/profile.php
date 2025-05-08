<?php
require_once 'bootstrap.php';

$controller = new ProfileController();

$user = $controller->getUser();

$enrolledClasses = $controller->getEnrolledClasses();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<?php require 'master.php';?>
<body>
    <div class="container mt-5">
        <h2>Edit Profile</h2>
        <form method="post" data-ajax="api/profile_api.php">
        <input type="hidden" name="UserId" value="<?php echo $user['UserId']; ?>">
            <div class="row mb-3">
                <label for="Email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $user['Email']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="FirstName" class="col-sm-2 form-label">First Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo $user['FirstName']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="LastName" class="col-sm-2 form-label">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo $user['LastName']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="Address" class="col-sm-2 form-label">Address</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $user['Address']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="Phone" class="col-sm-2 form-label">Phone</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo $user['Phone']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="SSN" class="col-sm-2 form-label">SSN</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="SSN" name="SSN" value="<?php echo $user['SSN']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <?php if (!empty($enrolledClasses) && count($enrolledClasses) > 0): ?>
        <div class="container mt-5">
            <h2>Enrolled Classes</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class ID</th>
                        <th>Course Name</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Max Enrollment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrolledClasses as $row): ?>
                        <tr>
                            <td><?php echo $row['ClassId']; ?></td>
                            <td><?php echo $row['CourseName']; ?></td>
                            <td><?php echo $row['Term']; ?></td>
                            <td><?php echo $row['Year']; ?></td>
                            <td><?php echo $row['MaxEnrollment']; ?></td>
                            <td>
                                <form method="post" data-ajax="api/profile_api.php">
                                    <input type="hidden" name="deleteClassId" value="<?php echo $row['ClassId']; ?>">
                                    <button type="submit" class="btn btn-danger">Unenroll</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
<?php require_once 'footer.php';?>
</body>
</html>