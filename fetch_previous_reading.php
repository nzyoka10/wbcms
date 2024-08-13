<?php

include("./config/functions.php");

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// Check if the user_id is set
if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Create a database connection
    $conn = connectDB();

    // Fetch the previous reading
    $previous_reading = fetchPreviousReading($user_id);

    // Return the previous reading as the response
    echo htmlspecialchars($previous_reading);
}
?>
