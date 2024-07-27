<?php
// Database configuration
$host = 'localhost'; // Database host
$user = 'root';      // Database username
$pass = '';          // Database password
$db   = 'wbcms_db'; // Database name

// Create a new database connection
function connectDB() {
    global $host, $user, $pass, $db;
    $conn = new mysqli($host, $user, $pass, $db);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>
