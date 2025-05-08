<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../api_errors.log');

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../bootstrap.php';

    $controller = new UsersController();

    $controller->handleAPIRequest();
} catch (Throwable $e) {
    error_log("API Fatal Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    echo json_encode([
        'success' => false,
        'message' => 'API Error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
    exit;
}