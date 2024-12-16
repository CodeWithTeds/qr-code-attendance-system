<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('image/granby.jpg');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .password-strength {
            margin-top: 5px;
            font-size: 0.875em;
        }
    </style>
</head>

<body>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']); // Don't convert to lowercase here for validation
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];

    $errors = [];

    // Name validation
    if (strlen($name) < 2 || strlen($name) > 50) {
        $errors[] = 'Name must be between 2 and 50 characters.';
    }
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = 'Name can only contain letters and spaces.';
    }

    // Email validation - strict gmail.com check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } else {
        $domain = substr($email, -10);
        if (strtolower($domain) !== '@gmail.com') {
            $errors[] = 'Only Gmail addresses are allowed.';
        } elseif ($domain !== '@gmail.com') {
            $errors[] = 'Email domain must be in lowercase ("@gmail.com").';
        }
    }

    // Password validation
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
    }

    // Role validation
    if (!in_array($role, ['instructor', 'admin'])) {
        $errors[] = 'Invalid role selected.';
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', strtolower($email)); // Convert to lowercase for checking
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Email already exists.';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', strtolower($email)); // Store email in lowercase
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'Your account has been created successfully.',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => {
                    window.location.href = 'Teacher-login.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'An error occurred during registration. Please try again.',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                html: '" . implode("<br>", array_map('htmlspecialchars', $errors)) . "',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        </script>";
    }
}
?>

    <div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Sign Up</h3>
                    <form action="" method="POST" id="signupForm" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="example@gmail.com">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback"></div>
                            <div class="password-strength"></div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="showPassword">
                            <label class="form-check-label" for="showPassword">Show Password</label>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select required id="role" name="role" class="form-control">
                                <option value="" hidden>Select role here</option>
                                <option value="instructor">Instructor</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="Teacher-login.php">Sign In</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm_password');
            passwordField.type = this.checked ? 'text' : 'password';
            confirmPasswordField.type = this.checked ? 'text' : 'password';
        });

        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let isValid = true;
            const errors = [];

            // Name validation
            const name = document.getElementById('name').value.trim();
            if (name.length < 2 || name.length > 50) {
                errors.push('Name must be between 2 and 50 characters.');
                isValid = false;
            }
            if (!/^[a-zA-Z ]*$/.test(name)) {
                errors.push('Name can only contain letters and spaces.');
                isValid = false;
            }

            // Email validation
            const email = document.getElementById('email').value.trim();
            if (!/^[a-z0-9._%+-]+@gmail\.com$/.test(email)) {
                errors.push('Please enter a valid Gmail address (lowercase only).');
                isValid = false;
            }

            // Password validation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password.length < 6) {
                errors.push('Password must be at least 6 characters long.');
                isValid = false;
            }
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(password)) {
                errors.push('Password must contain at least one uppercase letter, one lowercase letter, and one number.');
                isValid = false;
            }
            if (password !== confirmPassword) {
                errors.push('Passwords do not match.');
                isValid = false;
            }

            // Role validation
            const role = document.getElementById('role').value;
            if (!role) {
                errors.push('Please select a role.');
                isValid = false;
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Failed',
                    html: errors.join('<br>'),
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            } else {
                this.submit();
            }
        });

        // Real-time name validation
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value.trim();
            const feedback = this.nextElementSibling;
            
            if (!/^[a-zA-Z ]*$/.test(name)) {
                this.classList.add('is-invalid');
                feedback.style.display = 'block';
                feedback.textContent = 'Name can only contain letters and spaces';
            } else if (name.length < 2 || name.length > 50) {
                this.classList.add('is-invalid');
                feedback.style.display = 'block';
                feedback.textContent = 'Name must be between 2 and 50 characters';
            } else {
                this.classList.remove('is-invalid');
                feedback.style.display = 'none';
            }
        });

        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value.trim();
            const feedback = this.nextElementSibling;
            
            if (!/^[a-z0-9._%+-]+@gmail\.com$/.test(email)) {
                this.classList.add('is-invalid');
                feedback.style.display = 'block';
                if (/@GMAIL\.COM$/i.test(email)) {
                    feedback.textContent = 'Email must be in lowercase (example@gmail.com)';
                } else if (/@gmail\.com$/i.test(email)) {
                    feedback.textContent = 'Email must be in lowercase (example@gmail.com)';
                } else {
                    feedback.textContent = 'Please enter a valid Gmail address';
                }
            } else {
                this.classList.remove('is-invalid');
                feedback.style.display = 'none';
            }
        });

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const feedback = this.nextElementSibling;
            const strengthIndicator = document.querySelector('.password-strength');
            
            let strength = 0;
            let message = '';
            
            if (password.length >= 6) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    message = 'Weak password';
                    strengthIndicator.style.color = '#dc3545';
                    break;
                case 2:
                case 3:
                    message = 'Medium password';
                    strengthIndicator.style.color = '#ffc107';
                    break;
                case 4:
                    message = 'Strong password';
                    strengthIndicator.style.color = '#28a745';
                    break;
            }
            
            strengthIndicator.textContent = message;
            
            if (password.length < 6 || !/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(password)) {
                this.classList.add('is-invalid');
                feedback.style.display = 'block';
                feedback.textContent = 'Password must be at least 6 characters with uppercase, lowercase, and number';
            } else {
                this.classList.remove('is-invalid');
                feedback.style.display = 'none';
            }
        });

        // Real-time confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const feedback = this.nextElementSibling;
            
            if (password !== confirmPassword) {
                this.classList.add('is-invalid');
                feedback.style.display = 'block';
                feedback.textContent = 'Passwords do not match';
            } else {
                this.classList.remove('is-invalid');
                feedback.style.display = 'none';
            }
        });

        // Convert email to lowercase as user types
        document.getElementById('email').addEventListener('input', function() {
            const cursorPosition = this.selectionStart;
            this.value = this.value.toLowerCase();
            this.setSelectionRange(cursorPosition, cursorPosition);
        });
    </script>
</body>
</html>