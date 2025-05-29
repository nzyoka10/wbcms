<?php
/*
*  File: client.php
*  Description: This file handles the client management functionality of the WBCMS.
*  It allows users to register new clients, view existing clients, and manage client data.
*/

// Include the functions file
include("./config/functions.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form inputs
    $fullName = trim($_POST['fullName']);
    $pNumber = trim($_POST['pNumber']);
    $address = trim($_POST['address']);
    $meterId = trim($_POST['meter_id']);
    $firstReading = trim($_POST['first_reading']);
    $status = trim($_POST['status']);

    $accountNumber = generateAccountNumber();

    try {
        // Register client (account number is generated inside)
        if (registerClient($fullName, $pNumber, $address, $meterId, $firstReading, $status)) {
            $success_message = "Client registered successfully.";
        } else {
            $error_message = "Failed to register client.";
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>WBCMS | Clients</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

  <!-- Material Icons -->
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="css/bootstrap5.0.1.min.css">
  <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
  <link rel="stylesheet" href="css/dataTables.dataTables.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/customer.css">
  <link rel="stylesheet" href="css/bill.css">
</head>

<body>
  <div class="grid-container">

    <!-- Header -->
    <header class="header">
      <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
      </div>
      <div class="header-left">
        <h6 class="text-secondary">Water Billing & Customer Management System - <small class="text-success">CLIENTS</small></h6>
      </div>
      <div class="header-right text-primary">
        <a href="#"><span class="material-icons-outlined">notifications</span></a>
        <a href="#"><span class="material-icons-outlined">email</span></a>
        <a href="./logout.php"><span class="material-icons-outlined">account_circle</span></a>
      </div>
    </header>
    <!-- End Header -->

    <!-- Sidebar -->
    <aside id="sidebar">
      <div class="sidebar-title">
        <div class="sidebar-brand">
          <span class="material-icons-outlined">water_drop</span>&nbsp;WBCMS
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
      </div>

      <ul class="sidebar-list">
        <li class="sidebar-list-item"><a href="./dashboard.php"><span class="material-icons-outlined">speed</span>&nbsp;&nbsp;Dashboard</a></li>
        <li class="sidebar-list-item"><a href="./client.php"><span class="material-icons-outlined">group_add</span>&nbsp;&nbsp;Clients</a></li>
        <li class="sidebar-list-item"><a href="./billings.php"><span class="material-icons-outlined">payments</span>&nbsp;&nbsp;Billings</a></li>
        <li class="sidebar-list-item"><a href="./report.php"><span class="material-icons-outlined">receipt_long</span>&nbsp;&nbsp;Report</a></li>
        <li class="sidebar-list-item"><a href="./settings.php"><span class="material-icons-outlined">settings</span>&nbsp;&nbsp;Settings</a></li>
        <li class="sidebar-list-item"><a href="./logout.php"><span class="material-icons-outlined">logout</span>&nbsp;&nbsp;Logout</a></li>
      </ul>
    </aside>
    <!-- End Sidebar -->

    <!-- Main section -->
    <main class="main-container">
      <div class="row">
        <div class="container mt-0">
          <!-- Card Container -->
          <div class="card">

            <div class="d-flex justify-content-between align-items-center p-3">
              <h5 class="mb-0 text-dark">Listing of Clients</h5>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class='fas fa-plus'></i>&nbsp;Create New
              </button>
            </div>

            <div class="card-body">

              <!-- Display messages -->
              <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error_message); ?></div>
              <?php endif; ?>

              <?php if (!empty($success_message)) : ?>
                <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success_message); ?></div>
              <?php endif; ?>

              <!-- Table of clients -->
              <table class="table table-striped table-bordered cell-border">
                <thead>
                  <tr>
                    <th>Sn#</th>
                    <th>Data Created</th>
                    <th>Meter</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  try {
                    $clients = getClients();
                    if (!empty($clients)) {
                      foreach ($clients as $index => $client) {
                        echo "<tr>";
                        echo "<th scope='row'>" . htmlspecialchars($index + 1) . "</th>";
                        echo "<td>" . htmlspecialchars($client['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($client['client_name']) . "</td>";
                        echo "<td class='text-capitalize'><small>" . htmlspecialchars($client['status']) . "</small></td>";
                        echo "<td>
                              <div class='dropdown'>
                                <button class='btn btn-success btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                  Action
                                </button>
                                <ul class='dropdown-menu'>
                                  <li><a class='dropdown-item' href='viewClient.php?user_id=" . urlencode($client['user_id']) . "'><i class='fas fa-eye'></i> View</a></li>
                                  <li><a class='dropdown-item' href='editClient.php?user_id=" . urlencode($client['user_id']) . "'><i class='fas fa-edit text-primary'></i> Edit</a></li>
                                  <li><a class='dropdown-item text-danger' href='deleteClient.php?user_id=" . urlencode($client['user_id']) . "' onclick='return confirm(\"Are you sure you want to delete this Client?\")'><i class='fas fa-trash'></i> Delete</a></li>
                                </ul>
                              </div>
                            </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center text-danger'><strong>No clients found.</strong></td></tr>";
                    }
                  } catch (Exception $e) {
                    echo "<tr><td colspan='6' class='text-center'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                  }
                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- End Main section -->
<!-- Modal: Register Client -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form action="client.php" method="POST">
          <div class="mb-3">
            <label for="fullName" class="form-label">Client Full Name</label>
            <input type="text" class="form-control" name="fullName" id="fullName" placeholder="John Doe" required>
          </div>

          <div class="mb-3">
            <label for="pNumber" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" name="pNumber" id="pNumber" placeholder="2547XXXXXXXX" required>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Client Address</label>
            <input type="text" class="form-control" name="address" id="address" placeholder="1234 Street Name" required>
          </div>

          <div class="mb-3">
            <label for="meter_id" class="form-label">Meter Number</label>
            <input type="text" class="form-control" name="meter_id" id="meter_id" placeholder="Meter Number" readonly>
          </div>

          <div class="mb-3">
            <label for="first_reading" class="form-label">Meter First Reading</label>
            <input type="number" class="form-control" name="first_reading" id="first_reading" placeholder="Initial reading" required>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
              <option value="" selected disabled>Choose Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="pending">Pending</option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Register Client</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
<!-- End Modal -->


  </div>

  <!-- Bootstrap JS and Popper -->
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
<script>
  // Function to open the sidebar
  function openSidebar() {
    document.getElementById("sidebar").style.width = "250px";
  }

  // Function to close the sidebar
  function closeSidebar() {
    document.getElementById("sidebar").style.width = "0";
  }
  </script>
