<?php
// Include the database config file
require 'config.php';

// Function to check if username or email already exists
function userExists($conn, $username, $email) {
    $query = "SELECT * FROM user_tbl WHERE username=? OR email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to register a new user
function registerUser($conn, $username, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO user_tbl (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    return $stmt->execute();
}


// Function to verify user credentials for login
function verifyUser($conn, $email, $password) {
    $query = "SELECT * FROM user_tbl WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return false;
}

?>
