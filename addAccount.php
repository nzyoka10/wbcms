<?php
include("./config/functions.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $cust_id = htmlspecialchars($_POST['cust_id']);
    $meter_id = htmlspecialchars($_POST['meter_id']);
    $cust_name = htmlspecialchars($_POST['cust_name']);
    $first_reading = htmlspecialchars($_POST['first_reading']);
    $cust_pNumber = htmlspecialchars($_POST['cust_pNumber']);
    $account_status = htmlspecialchars($_POST['account_status']);
    $cust_address = htmlspecialchars($_POST['cust_address']);

    // Validate input data
    if (!empty($cust_id) && !empty($meter_id) && !empty($cust_name) && !empty($first_reading) && !empty($cust_pNumber) && !empty($account_status) && !empty($cust_address)) {
        // Call the function to add the account
        $message = newAccount($cust_id, $meter_id, $cust_name, $first_reading, $cust_pNumber, $account_status, $cust_address);
        
        // Provide feedback to the user
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '$message',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'customer.php'; // Redirect to the customer listing page
            });
        </script>";
    } else {
        // Missing input
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Input Error',
                text: 'Please fill all fields.',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
