<?php
include("./config/functions.php");

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
    $result = deleteClient($id);

    if ($result) {
        header('Location: customer.php');
    } else {
        echo "Error deleting client.";
    }
}
?>
