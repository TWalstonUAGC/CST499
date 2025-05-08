<?php
require_once __DIR__ . '/../bootstrap.php';

$controller = new ProfileController();

$controller->handleProfileUpdate();

$controller->handleUnenroll();