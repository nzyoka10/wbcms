<?php
session_start();

include("./config/functions.php");

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// Process form submission for adding or editing clients
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['id'])) {
    // Edit existing user details
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $meter_id = $_POST['meter_id'];
    $first_reading = $_POST['first_reading'];
    $status = $_POST['status'];

    // Call the editClient function and display the result
    echo editClient($id, $name, $email, $mobile, $address, $meter_id, $first_reading, $status);
  } else {
    // Add new client
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $meter_id = $_POST['meter_id'];
    $first_reading = $_POST['first_reading'];
    $status = $_POST['status'];

    // Call the addClient function and display the result
    echo addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status);
  }
}

// Fetch user data to pre-fill the form if editing
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);
  $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
  } else {
    echo '<div class="alert alert-danger" role="alert">User not found.</div>';
    exit;
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>WBCM | Dashboard</title>

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
        <h4 class="text-secondary"><strong>WBCM</strong>&nbsp;&nbsp;-&nbsp;&nbsp;Listing of Clients</h4>
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
        <span class="material-icons-outlined">water_drop</span> WBCM
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
      </div>

      <ul class="sidebar-list">
        <li class="sidebar-list-item">
          <a href="./dashboard.php">
            <span class="material-icons-outlined">dashboard</span>&nbsp;&nbsp;Dashboard
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./customer.php">
            <span class="material-icons-outlined">group</span>&nbsp;&nbsp;Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./billAccount.php">
            <span class="material-icons-outlined">paid</span>&nbsp;&nbsp;Billing
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="#">
            <span class="material-icons-outlined">poll</span>&nbsp;&nbsp;Reports
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
        <div class="container">

          <!-- Add account modal -->
          <div class="btnAdd float-right">
            <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">New Client</a>
          </div>

          <div class="form-group pull-right mt-2">
            <input type="text" class="search form-control" placeholder="Search by Name, Meter ID...">
          </div>

          <span class="counter pull-right"></span>
          <!-- Data table -->
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-12 mt-2">
              <table id="example" class="table table-hover table-bordered">
                <thead>
                  <tr class="text-capitalize">
                    <th>Sn&nbsp;#</th>
                    <th class="col-md-3 col-xs-3">Full Name</th>
                    <th class="col-md-3 col-xs-3">Meter ID</th>
                    <th class="col-md-3 col-xs-3">Email</th>
                    <th class="col-md-3 col-xs-3">Contact</th>
                    <th class="col-md-3 col-xs-3">Address</th>
                    <th class="col-md-3 col-xs-3">Status</th>
                    <th class="col-md-3 col-xs-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Fetch all clients
                  $result = getAllClients();

                  // Check if there are clients
                  if ($result['status'] === 'true') {
                    $clients = $result['data'];

                    // Initialize counter for serial number
                    $sn = 1;
                    // private $user_id;

                    // Loop through clients and display in table rows
                    foreach ($clients as $client) {
                      echo '<tr>';
                      echo '<td>' . $sn . '</td>'; // Display serial number
                      echo '<td>' . $client['username'] . '</td>';
                      echo '<td>' . $client['meter_id'] . '</td>';
                      echo '<td>' . $client['email'] . '</td>';
                      echo '<td>' . $client['contact'] . '</td>';
                      echo '<td>' . $client['address'] . '</td>';
                      echo '<td>' . $client['status'] . '</td>';
                      echo '<td>';
                      echo '<div class="btn-group">
                              <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item btn-success mx-auto" data-bs-toggle="modal" data-bs-target="#updateUserModal" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                              </ul>
                            </div>';

                      // echo '<button data-bs-toggle="modal" data-bs-target="#updateUserModal" 
                      //   class="btn btn-sm btn-success mx-auto d-block editbtn" data-id="' . $client['user_id'] . '">Edit</button><br>';


                      // echo '<button class="btn btn-sm btn-danger deleteBtn mx-auto d-flex" data-id="' 
                      //   . $client['user_id'] . '">Delete</button>';


                      echo '</td>';
                      echo '</tr>';

                      

                      // Increment counter for serial number
                      $sn++;
                    }
                  } else {
                    // Handle error from getAllClients
                    echo '<tr><td colspan="8">';

                    if (isset($result['message'])) {
                      // Display error message if available
                      echo $result['message'];
                    } else {
                      echo 'An error occurred while fetching clients.';
                    }
                    echo '</td></tr>';
                  }
                  ?>
                </tbody>
              </table>


            </div>
            <div class="col-md-2"></div>
          </div>

        </div>
      </div>

    </main>
    <!-- End Main -->

  </div>


   <!-- Script files -->
   <script src="js/bill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
    <script src="js/scripts.js"></script>




  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-green" id="exampleModalLabel">Add New Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="addUser" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3 row">
              <label for="addUserField" class="col-md-3 form-label">Full Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addUserField" name="name" placeholder="Client Name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addEmailField" class="col-md-3 form-label">Email</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="addEmailField" name="email" placeholder="Email Address">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addMobileField" class="col-md-3 form-label">Contact #</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addMobileField" name="mobile" placeholder="Phone Number">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addAddressField" class="col-md-3 form-label">Address</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addAddressField" name="address" placeholder="Nairobi, Kenya">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addMeterIdField" class="col-md-3 form-label">Meter ID</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addMeterIdField" name="meter_id" placeholder="Meter Number">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addMeterIdField" class="col-md-3 form-label">Initial Reading</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addFirstReadingField" name="first_reading" placeholder="Meter Number">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addStatusField" class="col-md-3 form-label">Status</label>
              <div class="col-md-9">
                <select name="status" id="addStatusField" class="form-control">
                  <option value="inactive">Inactive</option>
                  <option value="active">Active</option>
                </select>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal to Update user -->
  <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Client Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <?php
          // Check if user_id is provided in GET request
          // Check if ID is provided to fetch user details
          if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($conn, $_GET['user_id']);
            $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$id'");

            if ($result && mysqli_num_rows($result) > 0) {
              $user = mysqli_fetch_assoc($result);
            } else {
              echo '<div class="alert alert-danger" role="alert">User not found.</div>';
              exit;
            }
          } else {
            echo '<div class="alert alert-danger" role="alert">No user ID provided.</div>';
            exit;
          }
          ?>


          <form id="updateUser" method="POST" action="./customer/update_customer.php">
            <input type="hidden" name="id" id="id" value="<?php echo isset($user['user_id']) ? htmlspecialchars($user['user_id']) : ''; ?>">
            <input type="hidden" name="trid" id="trid" value="">

            <div class="mb-3 row">
              <label for="nameField" class="col-md-3 form-label">Full Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="nameField" name="name" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="emailField" class="col-md-3 form-label">Email</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="emailField" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="mobileField" class="col-md-3 form-label">Contact&nbsp;#</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="mobileField" name="mobile" value="<?php echo isset($user['contact']) ? htmlspecialchars($user['contact']) : ''; ?>" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addressField" class="col-md-3 form-label">Address</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addressField" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="meterIdField" class="col-md-3 form-label">Meter ID</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="meterIdField" name="meter_id" value="<?php echo isset($user['meter_id']) ? htmlspecialchars($user['meter_id']) : ''; ?>" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="statusField" class="col-md-3 form-label">Status</label>
              <div class="col-md-9">
                <select name="status" id="statusField" class="form-control" required>
                  <option value="inactive" <?php if (isset($user['status']) && $user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                  <option value="active" <?php if (isset($user['status']) && $user['status'] == 'active') echo 'selected'; ?>>Active</option>
                </select>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Update Record!</button>
            </div>
          </form>



        </div>

      </div>
    </div>
  </div>


</body>

</html>

