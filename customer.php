<?php
session_start();

include("./config/functions.php");


// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
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
</head>

<body>
  <div class="grid-container">

    <!-- Header -->
    <header class="header">
      <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
      </div>
      <div class="header-left">
        <h4 class="text-secondary"><strong>WBCM</strong>&nbsp;&nbsp;-&nbsp;&nbsp;Clients</h4>
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
          <span class="material-icons-outlined">water_drop</span> AquaBill
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
            <span class="material-icons-outlined">group</span>&nbsp;&nbsp;List of Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="#">
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

    <!-- Main -->
    <main class="main-container">

      <div class="row">
        <div class="container">

          <!-- Add account modal -->
          <div class="btnAdd">
            <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">New Client</a>
          </div>

          <!-- Data table -->
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-12 mt-2">
              <table id="example" class="table table-striped">
                <thead>
                  <tr class="text-capitalize">
                    <th>Sn&nbsp;#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Meter ID</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                      echo '<td>' . $client['email'] . '</td>';
                      echo '<td>' . $client['contact'] . '</td>';
                      echo '<td>' . $client['address'] . '</td>';
                      echo '<td>' . $client['meter_id'] . '</td>';
                      echo '<td>' . $client['status'] . '</td>';
                      echo '<td>';
                      echo '<button data-bs-toggle="modal" data-bs-target="#updateUserModal" class="btn btn-sm btn-success mx-auto d-block editbtn" data-id="' . $client['user_id'] . '">Edit</button><br>';
                      echo '<button class="btn btn-sm btn-danger deleteBtn mx-auto d-flex" data-id="' . $client['user_id'] . '">Delete</button>';
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


  <!-- ApexCharts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script src="js/scripts.js"></script>
  <!-- <script src="js/script.js"></script> -->


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


          <form id="updateUser" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" id="id" value="">
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
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </form>


        </div>
       
      </div>
    </div>
  </div>
<!-- 
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.editbtn').forEach(button => {
        button.addEventListener('click', function() {
          const userId = this.getAttribute('data-id');
          fetchUserData(userId);
        });
      });
    });

    function fetchUserData(userId) {
      fetch('get_user.php?id=' + userId)
        .then(response => response.json())
        .then(data => {
          document.getElementById('id').value = data.user_id;
          document.getElementById('nameField').value = data.username;
          document.getElementById('emailField').value = data.email;
          document.getElementById('mobileField').value = data.contact;
          document.getElementById('addressField').value = data.address;
          document.getElementById('meterIdField').value = data.meter_id;
          document.getElementById('statusField').value = data.status;
        })
        .catch(error => console.error('Error fetching user data:', error));
    }
  </script> -->



</body>

</html>

<!--  -->

<?php
// Initialize response array
$response = array();

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {


  // Sanitize input data
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $meter_id = mysqli_real_escape_string($conn, $_POST['meter_id']);
  $first_reading = mysqli_real_escape_string($conn, $_POST['first_reading']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  // Call addClient function to add user
  $result = addClient($name, $email, $mobile, $address, $meter_id, $first_reading, $status);

  // Set response based on addClient result
  if ($result['status'] === 'true') {
    $response['status'] = 'success';
    $response['message'] = $result['message'];
  } else {
    $response['status'] = 'error';
    $response['message'] = $result['message'];
  }
} else {
  // If POST data not received
  $response['status'] = 'error';
  $response['message'] = 'No data received.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);


// update user informations
// Get the user ID from the GET request
$user_id = $_GET['id'];

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$query = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($query);


// Check if form is submitted and 'id' is set in POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  // Sanitize and validate input data
  $id = $_POST['id'];
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
  $address = mysqli_real_escape_string($con, $_POST['address']);
  $meter_id = mysqli_real_escape_string($con, $_POST['meter_id']);
  $status = mysqli_real_escape_string($con, $_POST['status']);

  // SQL query to update user information
  $sql = "UPDATE users SET 
          username = '$name', 
          email = '$email', 
          contact = '$mobile', 
          address = '$address', 
          meter_id = '$meter_id', 
          status = '$status' 
          WHERE user_id = '$id'";

  // Execute SQL query
  if (mysqli_query($con, $sql)) {
      // Redirect to client.php with success message
      header("Location: customer.php?message=User information updated successfully.");
      exit();
  } else {
      // Handle database update error
      $error_message = "Error updating user information: " . mysqli_error($con);
  }
} else {
  // Handle invalid or empty form submission
  $error_message = "Invalid request. Please provide user ID.";
}

// Redirect to client.php with error message if any
header("Location: customer.php?error=" . urlencode($error_message));
exit();
?>