<?php
/**
 * Database configuration
 * @host: Database host
 * @user: Database username
 * @pass: Database password
 * @db:   Database name
 */
$host = 'localhost'; 
$user = 'root';      
$pass = '';          
$db   = 'wbcms_db'; 

/**
 * connectDB - Create a new database connection
 * Return: Database connection object on success, exits the script on failure
 */
function connectDB()
{
    global $host, $user, $pass, $db;

    // Initialize a new database connection using MySQLi
    $conn = new mysqli($host, $user, $pass, $db);
    
    // Check if the connection was successful
    if ($conn->connect_error) {
        // If the connection failed, exit the script with an error message
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Return the database connection object
    return $conn;
}
?>
