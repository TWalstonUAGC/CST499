<?php
require_once __DIR__ . '/../bootstrap.php';

$controller = new AuthController();

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $controller->handleLogout();
    exit;
}

$controller->handleLogin();