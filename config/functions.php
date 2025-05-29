<?php
/**
 * functions.php - Contains functions for user authentication, client management and billing operations.
 *
 * This file includes functions to register users, log in users, manage clients,
 * and handle billing operations such as creating bills and fetching billing history.
 */

// Include the database configuration file
require 'config.php';

// Start a new session or resume the existing session
session_start();

// Create a database connection
$conn = connectDB();

/**
 * userExists - Check if a user exists in the database
 * @email: The email address to check for existence in the database
 * Return: true if the user exists, false otherwise
 */
function userExists($email)
{
    global $conn;

    $query = "SELECT * FROM tbl_users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return true if a user with the provided email exists
    return $result->num_rows > 0;
}

/**
 * registerUser - Register a new user in the database
 * @username: The username of the new user
 * @email: The email address of the new user
 * @password: The password for the new user
 * Return: true if the registration is successful, false otherwise
 */
function registerUser($username, $email, $password)
{
    global $conn;

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO tbl_users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Return true if the insertion is successful
    return $stmt->execute();
}

/**
 * loginUser - Authenticate the user and log them into the system
 * @username: The username entered by the user
 * @password: The password entered by the user
 * Return: true if the login is successful, false otherwise
 */
function loginUser($username, $password)
{
    global $conn;

    // Prepare the SQL query to fetch the user's record by username
    $query = "SELECT * FROM tbl_users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password against the stored hash
        if (password_verify($password, $user['user_password'])) {
            // Set session variables to log the user in
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['user_id'] = $user['user_id'];

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Return false if the password is incorrect
            return false;
        }
    } else {
        // Return false if the user does not exist
        return false;
    }
}

/**
 * clientExists - Check if a client already exists based on unique fields
 * @meterId: The meter number to check
 * @contactNumber: The phone number to check
 * @excludeClientId: The ID of the client to exclude from the check (optional)
 * Return: true if a conflicting client exists, false otherwise
 */
function clientExists($meterId, $contactNumber, $excludeClientId = null)
{
    global $conn;

    // Prepare the SQL query with or without the client exclusion
    if ($excludeClientId) {
        $query = "SELECT * FROM tbl_clients WHERE (meter_number = ? OR contact_number = ?) AND user_id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $meterId, $contactNumber, $excludeClientId);
    } else {
        $query = "SELECT * FROM tbl_clients WHERE meter_number = ? OR contact_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $meterId, $contactNumber);
    }

    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Return true if a conflicting client exists
    return $result->num_rows > 0;
}

/**
 * registerClient - Register a new client in the database
 * @fullName: The name of the client
 * @pNumber: The phone number of the client
 * @address: The address of the client
 * @meterId: The meter number of the client
 * @firstReading: The initial meter reading
 * @status: The status of the client (active/inactive)
 * Return: true if the registration is successful, false otherwise
 */
function registerClient($fullName, $pNumber, $address, $meterId, $firstReading, $status)
{
    global $conn;

    // Check if the client already exists in the database
    if (clientExists($meterId, $pNumber)) {
        throw new Exception('A client with the same meter number or contact number already exists.');
    }

    // Prepare the SQL query to insert the new client's details
    $query = "INSERT INTO tbl_clients (client_name, contact_number, address, meter_number, meter_reading, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssss", $fullName, $pNumber, $address, $meterId, $firstReading, $status);

    // Execute the query and return true if the insertion is successful
    return $stmt->execute();
}

/**
 * updateClient - Updates client details by user_id
 * @param int $userId - The ID of the client
 * @param string $client_name - The client's full name
 * @param string $phone_number - The client's contat number
 * @param string $address - The client's address
 * @param string $meter_number - The meter number
 * @param string $meter_reading - The first meter reading
 * @param string $status - The status of the client
 */
function updateClient($userId, $client_name, $phone_number, $address, $meter_number, $meter_reading, $status) {
    global $conn;

    // SQL query to update client details
    $stmt = $conn->prepare("
        UPDATE tbl_clients 
        SET client_name = ?, contact_number = ?, address = ?, meter_number = ?, meter_reading = ?, status = ? 
        WHERE user_id = ?
    ");
    $stmt->bind_param("ssssssi", $client_name, $phone_number, $address, $meter_number, $meter_reading, $status, $userId);
    $stmt->execute();
}

/**
 * deleteClient - Deletes a client record by user_id
 * @param int $userId - The ID of the client
 */
function deleteClient($userId) {
    global $conn;

    // SQL query to delete a client
    $stmt = $conn->prepare("DELETE FROM tbl_clients WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

$total_paid_bills = 0;
$total_revenue = 0;

try {
    // Create SQL query to count and sum paid bills
    $sql = "SELECT COUNT(*) AS total_paid_bills, SUM(total) AS total_revenue
            FROM tbl_billinglist
            WHERE status = 1";
    
    // Prepare and execute the query
    $result = $conn->query($sql);

    // Fetch result
    if ($result) {
        $row = $result->fetch_assoc();
        $total_paid_bills = $row['total_paid_bills'];
        $total_revenue = $row['total_revenue'];
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}



// Function to fetch all clients from the database
function getClients()
{
    global $conn;

    $query = "SELECT * FROM tbl_clients";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
try {
    $clients = getClients();
} catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
}

// Function to count the number of registered clients
function countClients()
{
    global $conn;

    $query = "SELECT COUNT(*) as client_count FROM tbl_clients";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['client_count'];
    } else {
        throw new Exception('Database query failed: ' . $conn->error);
    }
}
try {
    // Get the number of registered clients
    $clientCount = countClients();
} catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
}

/**
 * fetchClients - Fetches all clients from the tbl_clients table
 * Return: An array of clients
 */
function fetchClients()
{
    global $conn;

    // SQL query to fetch all clients
    $query = "SELECT user_id, client_name FROM tbl_clients";
    $result = $conn->query($query);

    // Initialize an array to store clients
    $clients = array();

    if ($result->num_rows > 0) {
        // Loop through the result set and store each client in the array
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }

    return $clients;
}
/**
 * getClientById - Fetches client details by user_id
 * @param int $userId - The ID of the client
 * @return array|null - An associative array of client details or null if not found
 */
function getClientById($userId) {
    global $conn;

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT client_name, contact_number, address, meter_number, meter_reading, status, created_at FROM tbl_clients WHERE user_id = ?");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameter and execute
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Return data if found
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


/**
 * Fetch the previous reading for a client
 * @param int $userId
 * @return float
 */
function fetchPreviousReading($user_id)
{
    global $conn;
    $query = "SELECT meter_reading FROM tbl_clients WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('No previous reading found for the given user ID.');
    }

    $row = $result->fetch_assoc();
    return (float) $row['meter_reading'];
}

/**
 * Create or update a billing record
 * @param int $userId
 * @param string $readingDate
 * @param string $dueDate
 * @param float $currentReading
 * @param float $rate
 * @param float $totalBill
 * @param string $status
 * @return bool
 */
function billClient($userId, $readingDate, $dueDate, $currentReading, $rate, $totalBill, $status)
{
    global $conn;

    // Fetch previous reading from tbl_clients
    try {
        $previousReading = fetchPreviousReading($userId);
    } catch (Exception $e) {
        throw new Exception('Error fetching previous reading: ' . $e->getMessage());
    }

    // Calculate the total bill
    $totalBill = ($currentReading - $previousReading) * $rate;

    // Map status to tinyint (0 for pending, 1 for paid)
    $statusTinyInt = ($status === 'inactive') ? 0 : 1;

    // Prepare SQL statement for insert or update
    $query = "
        INSERT INTO tbl_billinglist 
        (user_id, reading_date, due_date, current_reading, previous_reading, rate, total, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        reading_date = VALUES(reading_date),
        due_date = VALUES(due_date),
        current_reading = VALUES(current_reading),
        previous_reading = VALUES(previous_reading),
        rate = VALUES(rate),
        total = VALUES(total),
        status = VALUES(status)
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Map status text to tinyint value
    $statusValue = ($status == 'Paid') ? 1 : 0;

    // Bind parameters: 'i' for integer, 's' for string, 'd' for double
    $stmt->bind_param("issddidi", $userId, $readingDate, $dueDate, $currentReading, $previousReading, $rate, $totalBill, $statusTinyInt);

    // Execute query
    return $stmt->execute();
}

/**
 * Fetch all billed clients with status text
 * @return array
 */
function fetchBilledClients()
{
    global $conn;

    $query = "SELECT b.bill_id, c.user_id, c.client_name, b.reading_date, b.due_date, b.current_reading, b.previous_reading, b.rate, b.total, b.status
              FROM tbl_billinglist b
              JOIN tbl_clients c ON b.user_id = c.user_id";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    $billedClients = [];
    while ($row = $result->fetch_assoc()) {
        // Map the numeric status to text
        $statusText = ($row['status'] == 0) ? 'Pending' : 'Paid';
        $row['status'] = $statusText;
        $billedClients[] = $row;
    }

    return $billedClients;
}

/**
 * Fetch the details of a specific bill using bill_id
 * @param int $billId
 * @return array
 * @throws Exception
 */
function getBillDetails($billId)
{
    global $conn;

    // Validate bill_id
    if (!is_int($billId) || $billId <= 0) {
        throw new InvalidArgumentException('Invalid bill_id provided.');
    }

    // Prepare SQL query to fetch bill details
    $query = "SELECT b.bill_id, c.user_id, c.client_name, b.reading_date, b.due_date, b.current_reading, b.previous_reading, b.rate, b.total, b.status
              FROM tbl_billinglist b
              JOIN tbl_clients c ON b.user_id = c.user_id
              WHERE b.bill_id = ?";

    // Prepare statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("i", $billId);

    // Execute query
    if (!$stmt->execute()) {
        throw new Exception('Database query execution failed: ' . $stmt->error);
    }

    // Fetch result
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('No record found for the given bill_id.');
    }

    return $result->fetch_assoc();
}

/**
 * Count the number of pending bills
 * @return int
 */
function countPendingBills()
{
    global $conn;

    // Query to count pending bills where status = 0 (Pending)
    $query = "SELECT COUNT(*) as pending_count FROM tbl_billinglist WHERE status = 0";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return intval($row['pending_count']);
    } else {
        throw new Exception('Database query failed: ' . $conn->error);
    }
}

// Get the count of pending bills
try {
    $pendingBillsCount = countPendingBills();
} catch (Exception $e) {
    $pendingBillsCount = 'N/A'; // Default value if an error occurs
    // Optionally, you can log the error or display a message
}


/**
 * Get the billing history for a specific client, including client name.
 *
 * @param int $client_id The ID of the client.
 * @param object $conn The database connection object.
 * @return array An associative array containing billing history and client name.
 * @throws Exception If there is an error with the query execution.
 */
function getBillingHistory($client_id, $conn) {
    // Ensure $client_id is an integer
    $client_id = intval($client_id);

    try {
        // Prepare the SQL query to fetch billing history along with client name
        $query = "SELECT b.bill_id, b.user_id, b.reading_date, b.due_date, b.previous_reading, b.current_reading, b.rate, b.total, b.status, c.client_name
                  FROM tbl_billinglist b
                  JOIN tbl_clients c ON b.user_id = c.client_name
                  WHERE b.user_id = ?
                  ORDER BY b.reading_date DESC"; // Ordering by most recent first

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Failed to prepare query: ' . $conn->error);
        }

        // Bind the client ID to the query
        $stmt->bind_param('i', $client_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all billing records for the client
        $billingHistory = $result->fetch_all(MYSQLI_ASSOC);

        // Free result set and close the statement
        $stmt->free_result();
        $stmt->close();

        return $billingHistory;

    } catch (Exception $e) {
        throw new Exception('An error occurred: ' . $e->getMessage());
    }
}



/**
 * update_settings - Updates the settings in the database based on user input.
 *
 * @param object $conn The database connection object.
 * @param array $form_data The form data submitted by the user.
 *
 * @return bool Returns true if the update was successful, false otherwise.
 */
function update_settings($conn, $form_data)
{
    // Sanitize user inputs
    $company_name = htmlspecialchars($form_data['company_name']);
    $company_email = htmlspecialchars($form_data['company_email']);
    $billing_rate = floatval($form_data['billing_rate']);
    $enable_notifications = isset($form_data['enable_notifications']) ? 1 : 0;

    // Prepare the SQL query to update settings
    $query = "UPDATE tbl_settings 
              SET company_name = ?, company_email = ?, billing_rate = ?, enable_notifications = ? 
              WHERE id = 1"; // Assuming there's only one settings row

    // Initialize a prepared statement
    $stmt = $conn->prepare($query);

    // Check if the statement was successfully prepared
    if (!$stmt) {
        // Log the error or handle it appropriately
        return false;
    }

    // Bind the sanitized inputs to the prepared statement
    $stmt->bind_param("ssdi", $company_name, $company_email, $billing_rate, $enable_notifications);

    // Execute the prepared statement
    $result = $stmt->execute();

    // Close the statement after execution
    $stmt->close();

    // Return the result of the execution
    return $result;
}


?>