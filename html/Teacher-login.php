<?php
session_start(); // Start session

require 'db.php'; // Include your database connection file

$error_message = ''; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and if the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Store user information in the session
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Set SweetAlert success message and target page in the session
        $_SESSION['success_message'] = "Welcome, " . $_SESSION['name'] . "! You have successfully logged in.";
        $_SESSION['redirect_target'] = $_SESSION['role'] == 'instructor' ? 'attendance.php' : 'admin-home.php';

        header('Location: Teacher-login.php'); // Reload the page to show the SweetAlert
        exit();
    } else {
        $error_message = 'Invalid email or password.';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('image/granby.jpg');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>
    <!-- Show SweetAlert error message -->
    <?php if (!empty($error_message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo $error_message; ?>',
                confirmButtonColor: '#3085d6'
            });
        });
    </script>
    <?php endif; ?>

    <!-- Show SweetAlert success message with redirection -->
    <?php if (!empty($_SESSION['success_message']) && !empty($_SESSION['redirect_target'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: '<?php echo $_SESSION['success_message']; ?>',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = '<?php echo $_SESSION['redirect_target']; ?>';
            });
        });
    </script>
    <?php 
        // Clear session success message and redirect target
        unset($_SESSION['success_message']);
        unset($_SESSION['redirect_target']);
    endif; ?>

    <div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Sign In</h3>
                    <form action="Teacher-login.php" method="POST" id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="show-password">
                                <label class="form-check-label" for="show-password">Show Password</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        <div class="text-center mt-3">
                            <p>New user? <a href="Teacher-signup.php">Create an account</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        const showPasswordCheckbox = document.getElementById('show-password');
        const passwordField = document.getElementById('password');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordField.type = this.checked ? 'text' : 'password';
        });

        // Form validation with SweetAlert
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields.'
                });
            }
        });
    </script>
</body>
</html>
