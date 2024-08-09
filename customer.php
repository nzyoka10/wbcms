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

  // Register the new client
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
  <title>WBCM | Clint Listing</title>

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
        <a href="#">
          <span class="material-icons-outlined">account_circle</span>
        </a>
      </div>
    </header>
    <!-- End Header -->

    <!-- Sidebar -->
    <aside id="sidebar">
      <div class="sidebar-title">
        <div class="sidebar-brand">
          <span class="material-icons-outlined">water_drop</span>&nbsp;WBCM
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
            <span class="material-icons-outlined">group_add</span>&nbsp;&nbsp;List of Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./billings.php">
            <span class="material-icons-outlined">payments</span>&nbsp;&nbsp;Billings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="#">
            <span class="material-icons-outlined">receipt_long</span>&nbsp;&nbsp;Monthly Report
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="#">
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

        <div class="container mt-4">
          <!-- Card Container -->
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Listing of Clients</h5>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                New Client
              </button>
            </div>

            <!-- Card Body -->
            <div class="card-body">
              <!-- Display error message if present -->
              <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo htmlspecialchars($error_message); ?>
                </div>
              <?php endif; ?>

              <!-- diplay registered clients -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Client Name</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Address</th>
                    <th scope="col">Meter Number</th>
                    <th scope="col">Meter Reading</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($clients)) : ?>
                    <?php foreach ($clients as $index => $client) : ?>
                      <tr>
                        <th scope="row"><?php echo htmlspecialchars($index + 1); ?></th>
                        <td><?php echo htmlspecialchars($client['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($client['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($client['address']); ?></td>
                        <td><?php echo htmlspecialchars($client['meter_number']); ?></td>
                        <td><?php echo htmlspecialchars($client['meter_reading']); ?></td>
                        <td><?php echo htmlspecialchars($client['status']); ?></td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              Click
                            </button>
                            <ul class="dropdown-menu">
                              <li>
                                <button type="button" class="btn btn-sm" data-bs-toggle="modal"
                                  data-bs-target="#viewClientModal"
                                  onclick="populateModal('<?php echo htmlspecialchars($client['client_name']); ?>', 
                                                    '<?php echo htmlspecialchars($client['contact_number']); ?>', 
                                                    '<?php echo htmlspecialchars($client['address']); ?>', 
                                                    '<?php echo htmlspecialchars($client['meter_number']); ?>', 
                                                    '<?php echo htmlspecialchars($client['meter_reading']); ?>', 
                                                    '<?php echo htmlspecialchars($client['status']); ?>')">
                                  <i class="fas fa-eye"></i> View
                                </button>
                              </li>
                              <li>
                                <a href="edit_client.php?id=<?php echo urlencode($client['user_id']); ?>" class="btn btn-sm text-success" title="Modify">
                                  <i class="fas fa-edit"></i> Edit
                                </a>
                              </li>
                              <li>
                                <a href="delete_client.php?id=<?php echo urlencode($client['user_id']); ?>" class="btn btn-sm text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this client?');">
                                  <i class="fas fa-trash"></i> Delete
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="8" class="text-center">No clients found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>


            </div>
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

  <!-- ==========***  Register new client form  ***========== -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Register Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" class="row g-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
              <button type="submit" class="btn btn-primary">Register Client</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- View Client Details Modal -->
  <div class="modal fade" id="viewClientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewClientModalLabel">Client Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Full Name:</strong> <span id="modalClientName"></span></p>
          <p><strong>Contact:</strong> <span id="modalClientContact"></span></p>
          <p><strong>Address:</strong> <span id="modalClientAddress"></span></p>
          <p><strong>Meter Number:</strong> <span id="modalClientMeterNumber"></span></p>
          <p><strong>Meter Reading:</strong> <span id="modalClientMeterReading"></span></p>
          <p><strong>Status:</strong> <span id="modalClientStatus"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function populateModal(name, contact, address, meterNumber, meterReading, status) {
      document.getElementById('modalClientName').textContent = name;
      document.getElementById('modalClientContact').textContent = contact;
      document.getElementById('modalClientAddress').textContent = address;
      document.getElementById('modalClientMeterNumber').textContent = meterNumber;
      document.getElementById('modalClientMeterReading').textContent = meterReading;
      document.getElementById('modalClientStatus').textContent = status;
    }
  </script>


</body>

</html>