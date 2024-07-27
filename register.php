<?php
// Include the functions file
require 'config/functions.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure form data is being captured correctly
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Check if username, email, and password are not empty
    if (!empty($username) && !empty($email) && !empty($password)) {
        if (registerUser($username, $email, $password)) {
            // Registration successful
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration successful',
                    text: 'You can now log in.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>";
        } else {
            // Registration failed
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registration failed',
                    text: 'Please try again later.',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    } else {
        // Missing input
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Input Error',
                text: 'Please fill all fields.',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>WBCM | Register</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Create Account</header>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="text" name="username" class="input-field" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <div class="input-submit">
                <button class="submit-btn" id="submit">Register</button>
            </div>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="index.php">Log In</a></p>
        </div>
    </div>
</body>
</html>
