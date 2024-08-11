<?php

// include("config.php");
include("config/functions.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // If not logged in, redirect to the login page
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>WBCMS | Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Material Icons -->
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <div class="grid-container">

    <!-- Header -->
    <header class="header">
      <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
      </div>
      <div class="header-left">
        <h4 class="text-primary">Water Billing & Customer Management System - <span class="text-success">DASHBOARD</span></h4>
      </div>
      <div class="header-right text-primary">
        <!-- Message Notification banner -->
        <a href="#">
          <span class="material-icons-outlined">notifications</span>
        </a>
        <a href="#">
          <span class="material-icons-outlined">email</span>
        </a>
        <a href="./logout.php">
          <span class="material-icons-outlined">account_circle</span>
        </a>
      </div>
    </header>
    <!-- End Header -->