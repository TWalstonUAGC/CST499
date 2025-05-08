<?php
require_once 'bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "User Management";
require_once 'master.php';
?>

<div class="container mt-4">
    <h2>User Management</h2>
    
    <div class="card">
        <div class="card-header">
            <h5>Users List</h5>
        </div>
        <div class="card-body">
            <div id="usersTable" class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                    </tbody>
                </table>
            </div>
            <div id="loadingMessage" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Loading users...</p>
            </div>
            <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                Error loading users data.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function loadUsers() {
        const tableBody = document.getElementById('usersTableBody');
        const loadingMessage = document.getElementById('loadingMessage');
        const errorMessage = document.getElementById('errorMessage');
        
        fetch('api/users_api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                loadingMessage.classList.add('d-none');
                
                if (data.success) {
                    const users = data.data.users;
                    
                    if (users.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
                    } else {
                        let html = '';
                        users.forEach(user => {
                            html += `
                                <tr>
                                    <td>${user.UserId}</td>
                                    <td>${user.Email}</td>
                                    <td>${user.FirstName || ''} ${user.LastName || ''}</td>
                                    <td>${user.Phone || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-user" data-id="${user.UserId}">View</button>
                                    </td>
                                </tr>
                            `;
                        });
                        tableBody.innerHTML = html;
                        
                        document.querySelectorAll('.view-user').forEach(button => {
                            button.addEventListener('click', function() {
                                const userId = this.getAttribute('data-id');
                                viewUserDetails(userId);
                            });
                        });
                    }
                } else {
                    errorMessage.textContent = data.message || 'Error loading users data.';
                    errorMessage.classList.remove('d-none');
                }
            })
            .catch(error => {
                loadingMessage.classList.add('d-none');
                errorMessage.classList.remove('d-none');
                errorMessage.textContent = 'Error: ' + error.message;
                console.error('Error fetching users:', error);
            });
    }
    
    function viewUserDetails(userId) {
        fetch(`api/users_api.php?userId=${userId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data && data.data.user) {
                    const user = data.data.user;
                    alert(`User Details:\nID: ${user.UserId}\nName: ${user.FirstName || ''} ${user.LastName || ''}\nEmail: ${user.Email}\nPhone: ${user.Phone || 'N/A'}\nAddress: ${user.Address || 'N/A'}`);

                } else {
                    alert('Error loading user details: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error loading user details: ' + error.message);
                console.error('Error fetching user details:', error);
            });
    }
    
    loadUsers();
});
</script>

<?php require_once 'footer.php'; ?>