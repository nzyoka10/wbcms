<?php
session_start();

// Include database connection and functions
include("./config/functions.php");

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission for adding or editing clients
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $meter_id = $_POST['meter_id'];
    $first_reading = $_POST['first_reading'];
    $status = $_POST['status'];

    // Check if 'id' is set to determine if we are editing an existing client
    if (isset($_POST['user_id'])) {
        // Edit existing user details
        $id = $_POST['user_id'];
        $result = editClient($id, $name, $email, $mobile, $address, $meter_id, $first_reading, $status);
        echo json_encode(['status' => $result ? 'success' : 'error']);
    } else {
        // Add new client
        $result = addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status);
        echo json_encode(['status' => $result ? 'success' : 'error']);
    }
    exit();
}


// Fetch user data to pre-fill the form if editing
if (isset($_GET['user_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$id'");

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo '<div class="alert alert-danger" role="alert">User not found.</div>';
        exit;
    }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Retrieve form data
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $mobile = $_POST['mobile'];
//     $address = $_POST['address'];
//     $meter_id = $_POST['meter_id'];
//     $first_reading = $_POST['first_reading'];
//     $status = $_POST['status'];

//     // Add client to the database
//     $result = addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status);

//     if ($result) {
//         echo json_encode(['status' => 'success']);
//     } else {
//         echo json_encode(['status' => 'error']);
//     }
// }
?>