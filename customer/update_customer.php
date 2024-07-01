<?php
// Include database connection
include('./config/functions.php');

// Check if form is submitted and 'id' is set in POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Sanitize and validate input data
    $id = $_POST['user_id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $meter_id = mysqli_real_escape_string($con, $_POST['meter_id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // SQL query to update user information
    $sql = "UPDATE users SET 
            username = '$name', 
            email = '$email', 
            contact = '$mobile', 
            address = '$address', 
            meter_id = '$meter_id', 
            status = '$status' 
            WHERE user_id = '$id'";

    // Execute SQL query
    if (mysqli_query($con, $sql)) {
        // Redirect to client.php with success message
        header("Location: ../customer.php?message=User information updated successfully.");
        exit();
    } else {
        // Handle database update error
        $error_message = "Error updating user information: " . mysqli_error($con);
    }
} else {
    // Handle invalid or empty form submission
    $error_message = "Invalid request. Please provide user ID.";
}

// Redirect to client.php with error message if any
header("Location: ../customer.php?error=" . urlencode($error_message));
exit();
?>
