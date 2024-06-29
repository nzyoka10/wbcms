<?php
// Include the functions file
require 'config/functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Check if user already exists
    if (userExists($conn, $username, $email)) {
        echo "Username or Email already exists.";
    } else {
        // Register the new user
        if (registerUser($conn, $username, $email, $password)) {
            // Redirect to login page
            header("Location: index.php");
            exit();
        } else {
            echo "Error: Unable to register user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>WBCM | Register</title>
</head>

<body>
    <div class="login-box">
        <div class="login-header">
            <header>Register Account</header>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="input-box">
                <input type="text" name="username" class="input-field" placeholder="Your Name" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email Address" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
            </div>

            <div class="input-submit">
                <button class="submit-btn" id="submit"></button>
                <label for="submit">Register</label>
            </div>
        </form>


        <div class="sign-up-link">
            <p>Already have account? <a href="index.php">Login</a></p>
        </div>
    </div>
</body>

</html>