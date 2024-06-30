<?php
// Include database connection file
include('connection.php');

// Initialize response array
$response = array();

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $meter_id = mysqli_real_escape_string($conn, $_POST['meter_id']);
    $first_reading = mysqli_real_escape_string($conn, $_POST['first_reading']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // SQL query to insert client data
    $sql = "INSERT INTO users (username, email, contact, address, meter_id, first_reading, status) 
            VALUES ('$name', '$email', '$mobile', '$address', '$meter_id', '$first_reading', '$status')";

    // Execute SQL query
    if (mysqli_query($conn, $sql)) {
        // Success
        $response['status'] = 'true';
        $response['message'] = 'Client added successfully.';
    } else {
        // Failure
        $response['status'] = 'false';
        $response['message'] = 'Failed to add client: ' . mysqli_error($conn);
    }
} else {
    // If POST data not received
    $response['status'] = 'false';
    $response['message'] = 'No data received.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
mysqli_close($conn);
?>
