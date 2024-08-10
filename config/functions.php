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
 * clientExists - Check if a client already exists based on unique fields
 * @meterId: The meter number to check
 * @contactNumber: The phone number to check
 * @excludeClientId: The ID of the client to exclude from the check (optional)
 * Return: true if a conflicting client exists, false otherwise
 */
function clientExists($meterId, $contactNumber, $excludeClientId = null)
{
    global $conn;

    // Prepare the SQL query with or without the client exclusion
    if ($excludeClientId) {
        $query = "SELECT * FROM tbl_clients WHERE (meter_number = ? OR contact_number = ?) AND user_id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $meterId, $contactNumber, $excludeClientId);
    } else {
        $query = "SELECT * FROM tbl_clients WHERE meter_number = ? OR contact_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $meterId, $contactNumber);
    }

    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Return true if a conflicting client exists
    return $result->num_rows > 0;
}

/**
 * registerClient - Register a new client in the database
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

    // Check if the client already exists in the database
    if (clientExists($meterId, $pNumber)) {
        throw new Exception('A client with the same meter number or contact number already exists.');
    }

    // Prepare the SQL query to insert the new client's details
    $query = "INSERT INTO tbl_clients (client_name, contact_number, address, meter_number, meter_reading, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssss", $fullName, $pNumber, $address, $meterId, $firstReading, $status);

    // Execute the query and return true if the insertion is successful
    return $stmt->execute();
}

/**
 * editClient - Edit an existing client's details in the database
 * @userId: The ID of the client to edit
 * @fullName: The updated name of the client
 * @pNumber: The updated phone number of the client
 * @address: The updated address of the client
 * @meterId: The updated meter number of the client
 * @firstReading: The updated meter reading
 * @status: The updated status of the client (active/inactive)
 * Return: true if the update is successful, false otherwise
 */
function editClient($userId, $fullName, $pNumber, $address, $meterId, $firstReading, $status)
{
    global $conn;

    // Check if the meter number or contact number is already in use by another client,
    // excluding the current client
    if (clientExists($meterId, $pNumber, $userId)) {
        throw new Exception('A client with the same meter number or contact number already exists.');
    }

    // Prepare the SQL query to update the client's information
    $sql = "UPDATE tbl_clients SET client_name = ?, contact_number = ?, address = ?, meter_number = ?, meter_reading = ?, status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssssi", $fullName, $pNumber, $address, $meterId, $firstReading, $status, $userId);

    // Execute the query and return true if the update is successful
    if ($stmt->execute()) {
        return true; // Success
    } else {
        return false; // Failure
    }
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

// Function to count the number of registered clients
function countClients()
{
    global $conn;

    $query = "SELECT COUNT(*) as client_count FROM tbl_clients";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['client_count'];
    } else {
        throw new Exception('Database query failed: ' . $conn->error);
    }
}
try {
    // Get the number of registered clients
    $clientCount = countClients();
} catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
}


?>
