<?php
// Include the database connection file
include('../config/config.php');

/**
 * Function to add a new client
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

    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $mobile = mysqli_real_escape_string($conn, $mobile);
    $address = mysqli_real_escape_string($conn, $address);
    $meter_id = mysqli_real_escape_string($conn, $meter_id);
    $first_reading = mysqli_real_escape_string($conn, $first_reading);
    $status = mysqli_real_escape_string($conn, $status);

    $sql = "INSERT INTO users (username, email, contact, address, meter_id, first_reading, status) 
            VALUES ('$name', '$email', '$mobile', '$address', '$meter_id', '$first_reading', '$status')";

    if (mysqli_query($conn, $sql)) {
        return array('status' => 'true', 'message' => 'Client added successfully.');
    } else {
        return array('status' => 'false', 'message' => 'Failed to add client: ' . mysqli_error($con));
    }
}

/**
 * Function to fetch all clients
 * @return array
 */
function getAllClients() {
    global $conn;

    $sql = "SELECT * FROM users";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
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
 * Function to search clients
 * @param string $searchTerm
 * @return array
 */
function searchClients($searchTerm) {
    global $conn;

    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

    $sql = "SELECT * FROM users 
            WHERE name LIKE '%$searchTerm%' 
            OR email LIKE '%$searchTerm%' 
            OR mobile LIKE '%$searchTerm%' 
            OR address LIKE '%$searchTerm%'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
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
 * Function to delete a client by ID
 * @param int $id
 * @return array
 */
function deleteClient($id) {
    global $conn;

    $id = mysqli_real_escape_string($conn, $id);

    $sql = "DELETE FROM users WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        return array('status' => 'true', 'message' => 'Client deleted successfully.');
    } else {
        return array('status' => 'false', 'message' => 'Failed to delete client: ' . mysqli_error($conn));
    }
}

// Close the database connection
mysqli_close($conn);
?>
