<?php
include("./config/functions.php");

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
    $result = getClientById($id);

    header('Content-Type: application/json');
    echo json_encode($result);
}
?>
