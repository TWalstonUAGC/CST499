<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'bootstrap.php';

$isLoggedIn = isset($_SESSION['userId']);

if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}

include_once 'master.php';
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome to the Student Registration System</h2>
            <p>Hello <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Student'; ?>,</p>
            <p>Use the navigation menu above to access different parts of the system:</p>
            <ul>
                <li><strong>Classes</strong> - Browse and register for available classes</li>
                <li><strong>Profile</strong> - View and update your profile information</li>
            </ul>
        </div>
    </div>
</div>

<?php
include_once 'footer.php';
?>