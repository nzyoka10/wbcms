<?php
// Include the functions file
require 'config/functions.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/login.css">
    <title>WBCM | Login</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Account Login</header>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email ID" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
            </div>
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check">
                    <label for="check">Remember me</label>
                </section>
                <section>
                    <a href="#">Forgot password</a>
                </section>
            </div>
            <div class="input-submit">
                <button class="submit-btn" id="submit">Sign In</button>
            </div>
        </form>

        <div class="sign-up-link">
        <p>Don't have an account?  <a href="register.php" style="color: red;">Register</a></p>
            <!-- <p>Don't have an account? <a href="register.php" id="signUpLink">Sign Up</a></p> -->
        </div>
    </div>
</body>
</html>
