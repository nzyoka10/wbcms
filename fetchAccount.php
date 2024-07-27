<?php
// fetchClients infomation
include("./config/functions.php");

// Fetch all clients
$result = getAllClients();

header('Content-Type: application/json');
echo json_encode($result);
?>
