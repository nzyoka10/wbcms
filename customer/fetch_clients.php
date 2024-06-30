<?php

// Include functions file
include('config/functions.php');

// Fetch all clients
$result = getAllClients();

// Prepare response data
$response = array();

if ($result['status'] === 'true') {
    $clients = $result['data'];

    // Format data for DataTables
    $data = array();
    foreach ($clients as $client) {
        $row = array(
            'id' => $client['id'],
            'name' => $client['username'],
            'email' => $client['email'],
            'mobile' => $client['contact'],
            'address' => $client['address'],
            'meter_id' => $client['meter_id'],
            'status' => $client['status'],
            // Add more fields as needed
        );
        $data[] = $row;
    }

    $response['data'] = $data;
} else {
    $response['data'] = array(); // No data found
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
