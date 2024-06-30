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

/**
 ** Function to add a new client
 * @param string $name
 * @param string $email
 * @param string $mobile
 * @param string $address
 * @param string $meter_id
 * @param string $first_reading
 * @param string $status
 * @return array
 */
function addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status) {
    global $conn;

    // Escape variables to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $mobile = mysqli_real_escape_string($conn, $mobile);
    $address = mysqli_real_escape_string($conn, $address);
    $meter_id = mysqli_real_escape_string($conn, $meter_id);
    $first_reading = mysqli_real_escape_string($conn, $first_reading);
    $status = mysqli_real_escape_string($conn, $status);

    // SQL query to insert client data into 'users' table
    $sql = "INSERT INTO users (username, email, contact, address, meter_id, first_reading, status) 
            VALUES ('$name', '$email', '$mobile', '$address', '$meter_id', '$first_reading', '$status')";

    // Execute SQL query
    if (mysqli_query($conn, $sql)) {
        return array('status' => 'true', 'message' => 'Client added successfully.');
    } else {
        return array('status' => 'false', 'message' => 'Failed to add client: ' . mysqli_error($conn));
    }
}

/**
 * Function to fetch all clients
 * @return array
 */
function getAllClients() {
    global $conn;

    // SQL query to fetch all clients from 'users' table
    $sql = "SELECT * FROM users";

    // Execute SQL query
    $result = mysqli_query($conn, $sql);

    // Check if query returned any results
    if (mysqli_num_rows($result) > 0) {
        $clients = array();
        // Fetch and store each row in the $clients array
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
        return array('status' => 'true', 'data' => $clients);
    } else {
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 * Function to search clients by name, email, mobile, or address
 * @param string $searchTerm
 * @return array
 */
function searchClients($searchTerm) {
    global $conn;

    // Escape search term to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

    // SQL query to search for clients based on name, email, mobile, or address
    $sql = "SELECT * FROM users 
            WHERE username LIKE '%$searchTerm%' 
            OR email LIKE '%$searchTerm%' 
            OR contact LIKE '%$searchTerm%' 
            OR address LIKE '%$searchTerm%'";

    // Execute SQL query
    $result = mysqli_query($conn, $sql);

    // Check if query returned any results
    if (mysqli_num_rows($result) > 0) {
        $clients = array();
        // Fetch and store each row in the $clients array
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
        return array('status' => 'true', 'data' => $clients);
    } else {
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 * Function to delete a client by ID
 * @param int $id
 * @return array
 */
function deleteClient($id) {
    global $conn;

    // Escape ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // SQL query to delete a client from 'users' table based on ID
    $sql = "DELETE FROM users WHERE id = '$id'";

    // Execute SQL query
    if (mysqli_query($conn, $sql)) {
        return array('status' => 'true', 'message' => 'Client deleted successfully.');
    } else {
        return array('status' => 'false', 'message' => 'Failed to delete client: ' . mysqli_error($conn));
    }
}

// Function to update client user information
function updateClient($user_id, $username, $email, $mobile, $address, $meter_id, $status) {
    global $conn;

    // Escape variables to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $mobile = mysqli_real_escape_string($conn, $mobile);
    $address = mysqli_real_escape_string($conn, $address);
    $meter_id = mysqli_real_escape_string($conn, $meter_id);
    $status = mysqli_real_escape_string($conn, $status);

    // Prepare the SQL query with placeholders
    $sql = "UPDATE users SET username = ?, email = ?, contact = ?, address = ?, meter_id = ?, status = ? WHERE user_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return array('status' => 'false', 'message' => 'Failed to prepare the SQL statement.');
    }

    // Bind the actual values to the placeholders
    $stmt->bind_param('ssssssi', $username, $email, $mobile, $address, $meter_id, $status, $user_id);

    // Execute the query
    $execute = $stmt->execute();

    // Check if the query was successful
    if ($execute) {
        $data = array(
            'status' => 'true',
            'message' => 'User information updated successfully.'
        );
    } else {
        $data = array(
            'status' => 'false',
            'message' => 'Failed to update user information: ' . $stmt->error
        );
    }

    // Close the statement
    $stmt->close();

    // Return the result
    return $data;
}

// Function to count all registered users
function countRegisteredUsers($conn) {
    // SQL query to count users
    $sql = "SELECT COUNT(*) as total_users FROM users";

    // Execute SQL query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the count
        $row = mysqli_fetch_assoc($result);
        $totalUsers = $row['total_users'];
        return $totalUsers;
    } else {
        // Handle query error
        return "Error: " . mysqli_error($conn);
    }
}


?>
