<?php
    error_reporting(E_ALL ^ E_NOTICE);
    require_once 'bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration Page</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
<?php require 'master.php';?>
<div class="container  text-center">
	<h1 class="pb-3">Welcome to the Registration Page</h1>
	<div id="success-message" class="alert alert-success d-none" role="alert">
            New record created successfully!
        </div>
	<div class="row">
		<div class="col-md-6">
		<form id="registrationForm" data-ajax="api/registration_api.php">
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="firstName" class="col-sm-2 col-form-label">First Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="lastName" class="col-sm-2 col-form-label">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="address" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="ssn" class="col-sm-2 col-form-label">SSN</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ssn" name="ssn" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
	</div>
	<div class="col-md-6">
		<img class="mx-2 img-fluid" src="https://leadgenapp.io/wp-content/uploads/2023/01/Register.jpg" alt="registration image">
	</div>
	</div>
    </div>
<?php require_once'footer.php';?>
</body>
</html>