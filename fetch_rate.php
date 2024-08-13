<?php

include("./config/functions.php");

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// Check if tariff_id is provided
if (isset($_POST['tariff_id'])) {
    $tariff_id = intval($_POST['tariff_id']);
    $rate = fetchRate($tariff_id);
    echo $rate;
} else {
    echo '0.0';  // Default response if tariff_id is not provided
}
