<?php
error_reporting(E_ALL ^ E_NOTICE);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'bootstrap.php';

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleLogin();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Page</title>
	<meta charset="utf-8">
	<meta name="viewport" width="device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<?php require 'master.php'; ?>
<div class="container vh-50 d-flex justify-content-center"></div>
<div class="row">
	<div class="col-md-5 justify-content-center">
		<img class="img-fluid px-4" src="https://cdn.wallpapersafari.com/31/78/wEbciL.jpg"/>
	</div>
	<div class="col-md-7 p-5">
	<h2 class="text-center">Login</h2>
		<form method="post" data-ajax="api/auth_api.php">
			<div class="mb-3">
				<label for="username" class="form-label">User Name</label>
				<input type="text" class="form-control" id="username" name="username" required>
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
</div>

<?php require_once 'footer.php';?>
</body>
</html>
