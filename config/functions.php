<?php

// Include the database configuration file
require 'config.php';

// Start a new session or resume the existing session
session_start();

// Create a database connection
$conn = connectDB();

/**
 * userExists - Check if a user exists in the database
 * @email: The email address to check for existence in the database
 * Return: true if the user exists, false otherwise
 */
function userExists($email)
{
    global $conn;

    $query = "SELECT * FROM tbl_users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return true if a user with the provided email exists
    return $result->num_rows > 0;
}

/**
 * registerUser - Register a new user in the database
 * @username: The username of the new user
 * @email: The email address of the new user
 * @password: The password for the new user
 * Return: true if the registration is successful, false otherwise
 */
function registerUser($username, $email, $password)
{
    global $conn;

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO tbl_users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Return true if the insertion is successful
    return $stmt->execute();
}

/**
 * loginUser - Authenticate the user and log them into the system
 * @username: The username entered by the user
 * @password: The password entered by the user
 * Return: true if the login is successful, false otherwise
 */
function loginUser($username, $password)
{
    global $conn;

    // Prepare the SQL query to fetch the user's record by username
    $query = "SELECT * FROM tbl_users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password against the stored hash
        if (password_verify($password, $user['user_password'])) {
            // Set session variables to log the user in
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['user_id'] = $user['user_id'];

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Return false if the password is incorrect
            return false;
        }
    } else {
        // Return false if the user does not exist
        return false;
    }
}

/**
 * Check if a client already exists based on unique fields
 * @meterId: The meter number to check
 * @contactNumber: The phone number to check
 * Return: true if the client exists, false otherwise
 */
function clientExists($meterId, $contactNumber)
{
    global $conn;

    $query = "SELECT * FROM tbl_clients WHERE meter_number = ? OR contact_number = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("ss", $meterId, $contactNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return true if a client with the provided meter number or contact number exists
    return $result->num_rows > 0;
}

/**
 * Register a new client in the database
 * @fullName: The name of the client
 * @pNumber: The phone number of the client
 * @address: The address of the client
 * @meterId: The meter number of the client
 * @firstReading: The initial meter reading
 * @status: The status of the client (active/inactive)
 * Return: true if the registration is successful, false otherwise
 */
function registerClient($fullName, $pNumber, $address, $meterId, $firstReading, $status)
{
    global $conn;

    // Check if the client already exists
    if (clientExists($meterId, $pNumber)) {
        throw new Exception('A client with the same meter number or contact number already exists.');
    }

    $query = "INSERT INTO tbl_clients (client_name, contact_number, address, meter_number, meter_reading, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("ssssss", $fullName, $pNumber, $address, $meterId, $firstReading, $status);

    // Return true if the insertion is successful
    return $stmt->execute();
}

// Function to fetch all clients from the database
function getClients()
{
    global $conn;
    
    $query = "SELECT * FROM tbl_clients";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

try {
    $clients = getClients();
} catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
}
?>
