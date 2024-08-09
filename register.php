<?php
// Include the functions file
require 'config/functions.php';

$errors = []; // Array to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user already exists
    if (userExists($email)) {
        $errors[] = "A user with that email already exists.";
    } else {
        // Attempt to register the new user
        if (registerUser($username, $email, $password)) {
            header("Location: index.php?success=1");
            exit();
        } else {
            $errors[] = "An error occurred during registration. Please try again.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">  
    <link rel="stylesheet" href="css/login.css">   
    <title>WBCM | Register</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Create Account</header>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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

        <div class="sign-up-link">
            <p>
                Already have an account?
                <a href="index.php" style="color: #000;">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
