<?php
// Include the database config file
require 'config.php';

/**
 * Check if a username or email already exists in the database.
 *
 * @param string $username Username to check
 * @param string $email Email to check
 * @return bool True if username or email exists, false otherwise
 */
function userExists($username, $email) {
    $conn = connectDB();
    $query = "SELECT 1 FROM user_tbl WHERE username = ? OR email = ?";
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
    $query = "INSERT INTO user_tbl (username, email, password) VALUES (?, ?, ?)";
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
    $query = "SELECT * FROM user_tbl WHERE email = ?";
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
 * Add a new client to the database.
 *
 * @param string $name Client's name
 * @param string $email Client's email
 * @param string $mobile Client's mobile number
 * @param string $address Client's address
 * @param string $meter_id Unique meter ID for the client
 * @param string $first_reading Initial meter reading
 * @param string $status Client's status
 * @return string Message indicating success or failure
 */
function addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status) {
    $conn = connectDB();

    // Check if meter_id already exists
    $query = "SELECT 1 FROM users WHERE meter_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $meter_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return 'Meter ID is already allocated. Please use a new one!';
    }

    // Insert new client
    $query = "INSERT INTO users (username, email, contact, address, meter_id, first_reading, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $name, $email, $mobile, $address, $meter_id, $first_reading, $status);
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

    $query = "SELECT * FROM users";
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
 * @param string $name Updated name
 * @param string $email Updated email
 * @param string $mobile Updated mobile number
 * @param string $address Updated address
 * @param string $meter_id Updated meter ID
 * @param string $first_reading Updated first reading
 * @param string $status Updated status
 * @return string Message indicating success or failure
 */
function editClient($id, $name, $email, $mobile, $address, $meter_id, $first_reading, $status) {
    $conn = connectDB();

    $query = "UPDATE users SET username = ?, email = ?, contact = ?, address = ?, meter_id = ?, first_reading = ?, status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $name, $email, $mobile, $address, $meter_id, $first_reading, $status, $id);
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
 * @param int $user_id Client ID to delete
 * @return array Status and message indicating success or failure
 */
function deleteClient($user_id) {
    $conn = connectDB();

    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
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

    $query = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR contact LIKE ? OR address LIKE ?";
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

    $query = "SELECT COUNT(*) as total_users FROM users";
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
