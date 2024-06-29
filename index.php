<?php
// Include the functions file
require 'config/functions.php';

// start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

    //   verify user credentials
    if($user = verifyUser($conn, $email, $password)){

        // setting session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['useraname'];
        $_SESSION['email'] = $user['email'];

        // redirect user to dashboard
        header('Location: dashboard.php');
        exit();
    }else{
        echo "<script>alert('Login failed.');</script>";
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
    <title>WBCM | Login</title>
</head>

<body>
    <div class="login-box">
        <div class="login-header">
            <header>Account Login</header>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email id" autocomplete="off" required>
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
                <button class="submit-btn" id="submit"></button>
                <label for="submit">Sign In</label>
            </div>
        </form>

        <div class="sign-up-link">
            <p>Don't have account? <a href="register.php">Sign Up</a></p>
        </div>
    </div>
</body>

</html>