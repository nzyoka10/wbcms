<?php
// Include the database config file
require 'config.php';

/**
 ** Function to check if user's username or email already exists
 * @param mysqli $conn
 * @param string $username
 * @param string $email
 * @return bool
 */
function userExists($conn, $username, $email) {
    $query = "SELECT * FROM user_tbl WHERE username=? OR email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

/**
 ** Function to add a new account user
 * @param mysqli $conn
 * @param string $username
 * @param string $email
 * @param string $password
 * @return bool
 */
function registerUser($conn, $username, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO user_tbl (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    return $stmt->execute();
}

/**
 ** Function to verify user credentials for login
 * @param mysqli $conn
 * @param string $email
 * @param string $password
 * @return mixed
 */
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
 ** Function to add a new client user (Customer)
 * @param string $name
 * @param string $email
 * @param string $mobile
 * @param string $address
 * @param string $meter_id
 * @param string $first_reading
 * @param string $status
 * @return string
 */
function addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status) {
    $conn = connectDB();

    $query = "SELECT * FROM users WHERE meter_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $meter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return '<script>alert("Hey, Meter A/C is already allocated. Allocate user new account!");</script>';
    }

    $query = "INSERT INTO users (username, email, contact, address, meter_id, first_reading, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $name, $email, $mobile, $address, $meter_id, $first_reading, $status);
    if ($stmt->execute()) {
        return '<script>alert("Client added successfully.");</script>';
    } else {
        return '<script>alert("Failed to add client.");</script>';
    }
}

/**
 ** Function to fetch all clients
 * @return array
 */
function getAllClients() {
    $conn = connectDB();

    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $clients = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
        return array('status' => 'true', 'data' => $clients);
    } else {
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 ** Function to update an existing client user details
 * @param int $id
 * @param string $name
 * @param string $email
 * @param string $mobile
 * @param string $address
 * @param string $meter_id
 * @param string $first_reading
 * @param string $status
 * @return string
 */
function editClient($id, $name, $email, $mobile, $address, $meter_id, $first_reading, $status) {
    $conn = connectDB();

    $query = "UPDATE users SET 
              username=?, email=?, contact=?, address=?, meter_id=?, first_reading=?, status=?
              WHERE user_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $name, $email, $mobile, $address, $meter_id, $first_reading, $status, $id);
    if ($stmt->execute()) {
        return '<script>alert("Client data updated successfully!");</script>';
    } else {
        return '<script>alert("Error updating client data.");</script>';
    }
}

/**
 ** Function to delete a client user by ID
 * @param int $user_id
 * @return array
 */
function deleteClient($user_id) {
    $conn = connectDB();

    $query = "DELETE FROM users WHERE user_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        return array('status' => 'true', 'message' => 'Client deleted successfully.');
    } else {
        return array('status' => 'false', 'message' => 'Failed to delete client.');
    }
}

/**
 ** Function to search clients by name, email, mobile, or address
 * @param string $searchTerm
 * @return array
 */
function searchClients($searchTerm) {
    $conn = connectDB();

    $query = "SELECT * FROM users 
              WHERE username LIKE ? 
              OR email LIKE ? 
              OR contact LIKE ? 
              OR address LIKE ?";
    $likeSearchTerm = "%$searchTerm%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $clients = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
        return array('status' => 'true', 'data' => $clients);
    } else {
        return array('status' => 'false', 'message' => 'No clients found.');
    }
}

/**
 ** Function to count all registered users
 * @param mysqli $conn
 * @return int|string
 */
function countRegisteredUsers($conn) {
    $query = "SELECT COUNT(*) as total_users FROM users";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_users'];
    } else {
        return "Error: " . mysqli_error($conn);
    }
}
?>
