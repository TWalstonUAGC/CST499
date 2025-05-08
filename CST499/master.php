<?php
    // Include bootstrap instead of manual session management
    require_once 'bootstrap.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="js/utils.js"></script>
<div class="m-4 p-5 bg-primary text-white rounded">
    <div class="container text-center">
        <h1></i>CST-499 Taylor Walston</h1>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light m-1 p-3 ">
    <div class="container-fluid">  
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMainAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>   
        <div class="collapse navbar-collapse" id="navbarMain">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="index.php"><i class="bi-house-door-fill"></i></i>&nbsp;Home</a>                
                <a class="nav-link" aria-current="page" href="classes.php"><i class="bi-mortarboard-fill"></i>&nbsp;Classes</a>              
                <a class="nav-link" aria-current="page" href="#"><i class="bi-exclamation-circle-fill"></i>&nbsp;About Us</a>              
                <a class="nav-link" aria-current="page" href="#"><i class="bi-megaphone-fill"></i>&nbsp;Contact Us</a>
            </div>
            <div class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php
                    if(isset($_SESSION['username'])) {
                        echo '<a class="nav-link" href="profile.php">Profile</a>';
                        echo '<a class="nav-link" href="#" id="logout-link">Logout</a>';
                        echo '<a class="nav-link" href="">'. $_SESSION['username'] .'</a>';
                    } else {
                        echo '<a class="nav-link" href="login.php">Login</a>';
                        echo '<a class="nav-link" href="registration.php">Registration</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</nav>

<!-- Add logout script for AJAX logout -->
<?php if(isset($_SESSION['username'])): ?>
<script>
    document.getElementById('logout-link').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Fixed path to ensure the API endpoint is correct
        fetch('api/auth_api.php?logout=1', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json();
                } else {
                    window.location.href = 'login.php';
                    throw new Error('Not JSON response');
                }
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirectTo || 'login.php';
                    }, 1000);
                } else {
                    showToast('Logout failed', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = 'login.php';
            });
    });
</script>
<?php endif; ?>
</body>
</html>
