<?php
// Include the functions file
include("./config/functions.php");


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
  // Retrieve form data
  $fullName = htmlspecialchars($_POST['fullName']);
  $pNumber = htmlspecialchars($_POST['pNumber']);
  $address = htmlspecialchars($_POST['address']);
  $meterId = htmlspecialchars($_POST['meter_id']);
  $firstReading = htmlspecialchars($_POST['first_reading']);
  $status = htmlspecialchars($_POST['status']);

  // register new client
  try {
    if (registerClient($fullName, $pNumber, $address, $meterId, $firstReading, $status)) {
      $success_message = 'Client registered successfully.';
    } else {
      $error_message = 'Error registering client.';
    }
  } catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
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
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        <h6 class="text-seconary">Water Billing & Customer Management System - <small class="text-success">CLIENTS</small></h6>
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

    <!-- Sidebar -->
    <aside id="sidebar">
      <div class="sidebar-title">
        <div class="sidebar-brand">
          <span class="material-icons-outlined">water_drop</span>&nbsp;WBCMS
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
      </div>

      <ul class="sidebar-list">
        <li class="sidebar-list-item">
          <a href="./dashboard.php">
            <span class="material-icons-outlined">speed</span>&nbsp;&nbsp;Dashboard
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./customer.php">
            <span class="material-icons-outlined">group_add</span>&nbsp;&nbsp;Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./billings.php">
            <span class="material-icons-outlined">payments</span>&nbsp;&nbsp;Billings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./report.php">
            <span class="material-icons-outlined">receipt_long</span>&nbsp;&nbsp;Report
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./settings.php">
            <span class="material-icons-outlined">settings</span>&nbsp;&nbsp;Settings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./logout.php">
            <span class="material-icons-outlined">logout</span>&nbsp;&nbsp;Logout
          </a>
        </li>

      </ul>
    </aside>
    <!-- End Sidebar -->

    <!-- Main section -->
    <main class="main-container">
      <div class="row">

        <div class="container mt-0">
          <!-- Card Container -->
          <div class="card">

            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 text-dark">Listing of Clients</h5>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class='fas fa-plus'></i>&nbsp;Create New
              </button>
            </div>

            <!-- data table to diplay account details -->
            <div class="card-body">
              <!-- Display error message if present -->
              <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo htmlspecialchars($error_message); ?>
                </div>
              <?php endif; ?>

              <!-- diplay registered clients -->
              <table class="table cell-border">
                <thead>
                  <tr>
                    <th scope="col">Sn#</th>
                    <th scope="col">Data Created</th>
                    <th scope="col">Meter</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                    <!-- <th scope="col"></th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php

                  // Fetch clients and output HTML
                  try {
                    $clients = getClients();

                    if (!empty($clients)) {
                      foreach ($clients as $index => $client) {
                        echo "<tr>";
                        echo "<th scope='row'>" . htmlspecialchars($index + 1) . "</th>";
                        echo "<td>" . htmlspecialchars($client['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($client['client_name']) . "</td>";

                        // echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
                        // echo "<td>" . htmlspecialchars($client['meter_reading']) . "</td>";
                        echo "<td class='text-capitalize text-bold'><small>" . htmlspecialchars($client['status']) . "</small></td>";
                        echo "
                            <td>
                              <div class='dropdown'>
                                <button class='btn btn-success btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                  Action
                                </button>
                                <ul class='dropdown-menu'>
                                  <li><a class='dropdown-item' href='viewClient.php?user_id=" . urlencode($client['user_id']) . "' class='btn btn-info btn-sm'><i class='fas fa-eye'></i>&nbsp;View</a></li>
                                  <li><a class='dropdown-item' href='editClient.php?user_id=" . urlencode($client['user_id']) . "' class='btn btn-warning btn-sm'><i class='fas fa-edit text-primary'></i>&nbsp;Edit</a></li>
                                  <li><a class='dropdown-item' href='deleteClient.php?user_id=" . urlencode($client['user_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this Client?\")'><i class='fas fa-trash text-danger'></i>&nbsp;Delete</a></li>
                                </ul>
                              </div>
                            </td>";



                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='8' class='text-center text-danger'><strong>No clients found.</strong></td></tr>";
                    }
                  } catch (Exception $e) {
                    echo "<tr><td colspan='8' class='text-center'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                  }

                  ?>

                </tbody>
              </table>

            </div>

            <script>
              function refreshClientTable() {
                // Perform an AJAX request to fetch the latest clients
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_clients.php', true);

                xhr.onreadystatechange = function() {
                  if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the table body with the fetched HTML
                    document.querySelector('table tbody').innerHTML = xhr.responseText;
                  }
                };

                xhr.send();
              }

              // Set the interval to refresh the table every 5 seconds (5000 ms)
              setInterval(refreshClientTable, 5000);

              // Call the function initially to load data immediately
              refreshClientTable();
            </script>


          </div>
        </div>

        <!-- JS Dependencies -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>




      </div>
    </main>
    <!-- End Main section -->

    <!-- JS Dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
  </div>

  <!-- ==========***  Create new client ***========== -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Register Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" class="row g-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" id="registerClientId" name="user_id">
            <div class="col-md-6">
              <label for="fullName" class="form-label">Client's Name</label>
              <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Full name" required>
            </div>
            <div class="col-md-6">
              <label for="pNumber" class="form-label">Contact</label>
              <input type="text" class="form-control" id="pNumber" name="pNumber" placeholder="Phone number" required>
            </div>
            <div class="col-md-6">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" name="address" placeholder="1234, Katani" required>
            </div>
            <div class="col-md-6">
              <label for="meter_id" class="form-label">Meter Number</label>
              <input type="text" class="form-control" id="meter_id" name="meter_id" placeholder="Meter number" required>
            </div>
            <div class="col-md-6">
              <label for="first_reading" class="form-label">Meter Reading</label>
              <input type="text" class="form-control" id="first_reading" name="first_reading" placeholder="0.0" required>
            </div>
            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" class="form-select" required>
                <option value="" disabled selected>Choose...</option>
                <option value="inactive">Inactive</option>
                <option value="active">Active</option>
              </select>
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-sm btn-success">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



</body>

</html>