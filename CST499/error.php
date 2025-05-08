<?php
    error_reporting(E_ALL ^ E_NOTICE);
    require_once 'bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile Page</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
<?php require 'master.php';?>
<div class="container text-center">
    <h1>Welcome to the Error Page</h1>
</div>
<?php require_once'footer.php';?>
</body>