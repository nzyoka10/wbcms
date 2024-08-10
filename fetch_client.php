<?php
// fetch_clients.php

// Include the functions file
include("./config/functions.php");

// Create a database connection
$conn = connectDB();

// Function to fetch all clients from the database
// function getClients()
// {
//     global $conn;

//     $query = "SELECT * FROM tbl_clients";
//     $result = $conn->query($query);

//     if (!$result) {
//         throw new Exception('Database query failed: ' . $conn->error);
//     }

//     return $result->fetch_all(MYSQLI_ASSOC);
// }

// Fetch clients and output HTML
try {
    $clients = getClients();

    if (!empty($clients)) {
        foreach ($clients as $index => $client) {
            echo "<tr>";
            echo "<th scope='row'>" . htmlspecialchars($index + 1) . "</th>";
            echo "<td>" . htmlspecialchars($client['client_name']) . "</td>";
            echo "<td>" . htmlspecialchars($client['contact_number']) . "</td>";
            echo "<td>" . htmlspecialchars($client['address']) . "</td>";
            echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
            echo "<td>" . htmlspecialchars($client['meter_reading']) . "</td>";
            echo "<td>" . htmlspecialchars($client['status']) . "</td>";
            echo "<td>
                    <div class='dropdown'>
                        <button class='btn btn-success btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>Click</button>
                        <ul class='dropdown-menu'>
                            <li>
                                <button type='button' class='btn btn-sm' data-bs-toggle='modal' data-bs-target='#viewClientModal' onclick=\"populateViewModal(
                                    '" . htmlspecialchars($client['client_name']) . "', 
                                    '" . htmlspecialchars($client['contact_number']) . "', 
                                    '" . htmlspecialchars($client['address']) . "', 
                                    '" . htmlspecialchars($client['meter_number']) . "', 
                                    '" . htmlspecialchars($client['meter_reading']) . "', 
                                    '" . htmlspecialchars($client['status']) . "')\">
                                    <i class='fas fa-eye'></i>&nbsp;View
                                </button>
                            </li>
                            <li>
                                <button type='button' class='btn btn-sm' data-bs-toggle='modal' data-bs-target='#editClientModal' onclick=\"populateEditModal(
                                    '" . urlencode($client['user_id']) . "', 
                                    '" . htmlspecialchars($client['client_name']) . "', 
                                    '" . htmlspecialchars($client['contact_number']) . "', 
                                    '" . htmlspecialchars($client['address']) . "', 
                                    '" . htmlspecialchars($client['meter_number']) . "', 
                                    '" . htmlspecialchars($client['meter_reading']) . "', 
                                    '" . htmlspecialchars($client['status']) . "')\">
                                    <i class='fas fa-edit'></i>&nbsp;Edit
                                </button>
                            </li>
                            <li>
                                <a href='delete_client.php?id=" . urlencode($client['user_id']) . "' class='btn btn-sm text-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete this client?');\">
                                    <i class='fas fa-trash'></i>&nbsp;Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>No clients found.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='8' class='text-center'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
