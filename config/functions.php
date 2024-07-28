<?php
// Include the database config file
require 'config.php';

// Start session
session_start();

/**
 * Check if a username or email already exists in the database.
 *
 * @param string $username Username to check
 * @param string $email Email to check
 * @return bool True if username or email exists, false otherwise
 */
function userExists($username, $email) {
    $conn = connectDB();
    $query = "SELECT 1 FROM tbl_users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

/**
 * Register a new user with a hashed password.
 *
 * @param string $username Username for the new user
 * @param string $email Email for the new user
 * @param string $password Plain-text password for the new user
 * @return bool True if user registered successfully, false otherwise
 */
function registerUser($username, $email, $password) {
    $conn = connectDB();
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO tbl_users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

/**
 * Verify user credentials for login.
 *
 * @param string $email Email of the user trying to log in
 * @param string $password Plain-text password provided by the user
 * @return mixed User data if credentials are correct, false otherwise
 */
function verifyUser($email, $password) {
    $conn = connectDB();
    $query = "SELECT * FROM tbl_users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $stmt->close();
            $conn->close();
            return $user;
        }
    }
    $stmt->close();
    $conn->close();
    return false;
}

/**
 * Register/ADD a new Customer Account to the database.
 *
 * @param string $cust_id Client's ID
 * @param string $meter_id Unique meter ID for the client
 * @param string $cust_name Client's full name
 * @param int $first_reading Initial meter reading
 * @param string $cust_pNumber Client's phone number
 * @param string $account_status Client's status (active/inactive)
 * @param string $cust_address Client's address
 * @return string Message indicating success or failure
 */
function newAccount($cust_id, $meter_id, $cust_name, $first_reading, $cust_pNumber, $account_status, $cust_address) {
    $conn = connectDB();

    // Check if the connection was successful
    if ($conn === false) {
        return 'Database connection failed.';
    }

    // Check if meter_id already exists
    $query = "SELECT 1 FROM tbl_account WHERE meter_id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        $conn->close();
        return 'Failed to prepare statement.';
    }

    $stmt->bind_param("s", $meter_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return 'Meter ID is already allocated. Please use a new one!';
    }

    $stmt->close();

    // Insert new client
    $query = "INSERT INTO tbl_account (cust_id, meter_id, cust_name, first_reading, cust_pNumber, account_status, cust_address) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        $conn->close();
        return 'Failed to prepare statement.';
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sssisss", $cust_id, $meter_id, $cust_name, $first_reading, $cust_pNumber, $account_status, $cust_address);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return 'Client added successfully.';
    } else {
        $stmt->close();
        $conn->close();
        return 'Failed to add client. Please try again.';
    }
}

/**
 * Fetch all clients from the database.
 *
 * @return array Array containing client data or an error message
 */
function getAllClients() {
    $conn = connectDB();

    $query = "SELECT * FROM tbl_account";
    $result = $conn->query($query);
    if ($result) {
        $clients = array();
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        $conn->close();
        return array('status' => 'true', 'data' => $clients);
    } else {
        $conn->close();
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 * Update details of an existing client.
 *
 * @param int $id Client ID
 * @param string $cust_name Updated name
 * @param string $cust_pNumber Updated mobile number
 * @param string $cust_address Updated address
 * @param string $meter_id Updated meter ID
 * @param string $first_reading Updated first reading
 * @param string $account_status Updated status
 * @return string Message indicating success or failure
 */
function editClient($id, $cust_name, $cust_pNumber, $cust_address, $meter_id, $first_reading, $account_status) {
    $conn = connectDB();

    $query = "UPDATE tbl_account SET cust_name = ?, cust_pNumber = ?, cust_address = ?, meter_id = ?, first_reading = ?, account_status = ? WHERE cust_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisss", $cust_name, $cust_pNumber, $cust_address, $meter_id, $first_reading, $account_status, $id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return 'Client data updated successfully!';
    } else {
        $stmt->close();
        $conn->close();
        return 'Error updating client data.';
    }
}

/**
 * Delete a client from the database by their ID.
 *
 * @param int $cust_id Client ID to delete
 * @return array Status and message indicating success or failure
 */
function deleteClient($cust_id) {
    $conn = connectDB();

    $query = "DELETE FROM tbl_account WHERE cust_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cust_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return array('status' => 'true', 'message' => 'Client deleted successfully.');
    } else {
        $stmt->close();
        $conn->close();
        return array('status' => 'false', 'message' => 'Failed to delete client.');
    }
}

/**
 * Search for clients based on various criteria.
 *
 * @param string $searchTerm Search term for filtering clients
 * @return array Array containing client data or an error message
 */
function searchClients($searchTerm) {
    $conn = connectDB();

    $query = "SELECT * FROM tbl_account WHERE cust_name LIKE ? OR meter_id LIKE ? OR cust_pNumber LIKE ? OR cust_address LIKE ?";
    $likeSearchTerm = "%$searchTerm%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $clients = array();
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        $stmt->close();
        $conn->close();
        return array('status' => 'true', 'data' => $clients);
    } else {
        $stmt->close();
        $conn->close();
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 * Count the total number of registered users.
 *
 * @return int|string Total number of users or an error message
 */
function countRegisteredUsers() {
    $conn = connectDB();

    $query = "SELECT COUNT(*) as total_users FROM tbl_account";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total_users'];
    } else {
        $conn->close();
        return "Error: " . $conn->error;
    }
}
?>
