<?php

include("./config/functions.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// check if user account is created
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ensure form data is being captured correctly
  $cust_id = htmlspecialchars($_POST['customer_id'] ?? '');
  $meter_id = htmlspecialchars($_POST['meter_id'] ?? '');
  $cust_name = htmlspecialchars($_POST['customer_name'] ?? '');
  $first_reading = htmlspecialchars($_POST['first_reading'] ?? '');
  $cust_pNumber = htmlspecialchars($_POST['customer_pNumber'] ?? '');
  $account_status = htmlspecialchars($_POST['account_status'] ?? '');
  $cust_address = htmlspecialchars($_POST['customer_address'] ?? '');

  // Check if all required fields are not empty
  if (!empty($cust_id) && !empty($meter_id) && !empty($cust_name) && !empty($first_reading) && !empty($cust_pNumber) && !empty($account_status) && !empty($cust_address)) {
      if (newAccount($cust_id, $meter_id, $cust_name, $first_reading, $cust_pNumber, $account_status, $cust_address)) {
          // Registration successful
          echo "<script>
              Swal.fire({
                  icon: 'success',
                  title: 'Registration successful',
                  text: 'You can now log in.',
                  confirmButtonText: 'OK'
              }).then(function() {
                  window.location.href = 'index.php';
              });
          </script>";
      } else {
          // Registration failed
          echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Registration failed',
                  text: 'Please try again later.',
                  confirmButtonText: 'OK'
              });
          </script>";
      }
  } else {
      // Missing input
      echo "<script>
          Swal.fire({
              icon: 'error',
              title: 'Input Error',
              text: 'Please fill all fields.',
              confirmButtonText: 'OK'
          });
      </script>";
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
                    <th class="col-md-3 col-xs-3">Customer ID</th>
                    <th class="col-md-3 col-xs-3">Meter ID</th>
                    <th class="col-md-3 col-xs-3">Full Name</th>
                    <th class="col-md-3 col-xs-3">Reading</th>
                    <th class="col-md-3 col-xs-3">Mobile</th>
                    <th class="col-md-3 col-xs-3">Status</th>
                    <th class="col-md-3 col-xs-3">Address</th>
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

                    // Loop through clients and display in table rows
                    foreach ($clients as $client) {
                      echo '<tr>';
                      echo '<td>' . $sn . '</td>'; // Display serial number
                      echo '<td>' . htmlspecialchars($client['customer_id']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['meter_id']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['customer_name']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['first_reading']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['customer_pNumber']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['account_status']) . '</td>';
                      echo '<td>' . htmlspecialchars($client['customer_address']) . '</td>';
                      echo '<td>';
                      echo '<div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item btn-success mx-auto" data-bs-toggle="modal" data-bs-target="#updateUserModal" href="#" data-id="' . htmlspecialchars($client['id']) . '">Edit</a></li>
                                                        <li><a class="dropdown-item btn-danger mx-auto" href="#" data-id="' . htmlspecialchars($client['id']) . '">Delete</a></li>
                                                    </ul>
                                                </div>';
                      echo '</td>';
                      echo '</tr>';
                      $sn++;
                    }
                  } else {
                    echo '<tr><td colspan="8">No clients found</td></tr>';
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

    <!-- Add Client Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-success" id="addUserModalLabel">Register New Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addUser" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
              <input type="hidden" name="id" id="clientId">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customer_Id">Customer ID</label>
                    <input type="text" class="form-control" name="cust_id" id="customer_Id" placeholder="12345678" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="meter_Id">Meter ID</label>
                    <input type="text" class="form-control" name="meter_id" id="meter_Id" placeholder="Ac-000/0" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customer_name">Full Name</label>
                    <input type="text" class="form-control" name="cust_name" id="customer_name" placeholder="Full Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="first_reading">First Reading</label>
                    <input type="number" class="form-control" name="first_reading" id="first_reading" placeholder="0.0" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customer_pNumber">Phone Number</label>
                    <input type="text" class="form-control" name="cust_pNumber" id="customer_pNumber" placeholder="+254 700-090-100" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cust_account_status">Status</label>
                    <select class="form-control" name="account_status" id="cust_account_status" required>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customer_address">Address</label>
                    <input type="text" class="form-control" name="cust_address" id="customer_address" placeholder="Katani, Syokimau" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Edit Client Modal -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateUserModalLabel">Edit Client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="clientEditForm" method="POST">
              <input type="hidden" name="id" id="editClientId">
              <div class="row">
                <!-- Similar fields as the add client modal -->
                <!-- Make sure to populate the fields with the existing client data -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="editName">Full Name</label>
                    <input type="text" class="form-control" name="name" id="editName" required>
                  </div>
                </div>
                <!-- Add other fields similarly -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- JS Dependencies -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <!-- sweet alert cdnjs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <!-- Custome JavaScript -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const addUserModal = document.getElementById('addUserModal');

    // Handle form submission for adding clients
    addUserModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const clientId = button.getAttribute('data-id');
      const form = addUserModal.querySelector('form');

      if (clientId) {
        form.action = 'updateAccount.php'; // PHP script to handle client updates
        fetch(`getClientData.php?id=${clientId}`) // Fetch existing client data
          .then(response => response.json())
          .then(data => {
            document.getElementById('customer_id').value = data.customer_id;
            document.getElementById('meter_id').value = data.meter_id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('first_reading').value = data.first_reading;
            document.getElementById('customer_pNumber').value = data.customer_pNumber;
            document.getElementById('cust_account_status').value = data.cust_account_status;
            document.getElementById('customer_address').value = data.customer_address;
          });
      } else {
        form.action = 'addAccount.php'; // PHP script to handle new client addition
        form.reset(); // Reset form fields
      }
    });

    // Handle form submission
    const addUserForm = document.getElementById('addUser');
    if (addUserForm) {
      addUserForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(addUserForm);

        fetch(addUserForm.action, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            Swal.fire({
              title: 'Success!',
              text: 'Account has been added successfully.',
              icon: 'success'
            }).then(() => {
              // Reload the DataTable
              $('#example').DataTable().ajax.reload();
              // Close modal
              $('#addUserModal').modal('hide');
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: 'There was an issue adding/updating the account details.',
              icon: 'error'
            });
          }
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred.',
            icon: 'error'
          });
        });
      });
    }

    // Handle deletion
    document.querySelectorAll('.btn-danger').forEach(function(button) {
      button.addEventListener('click', function() {
        const clientId = this.getAttribute('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: 'You will not be able to recover this client!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'deleteClient.php?id=' + clientId;
          }
        });
      });
    });

    // Initialize DataTable
    $('#example').DataTable({
      "ajax": "fetchClients.php", // URL to fetch client data
      "processing": true,
      "serverSide": true
    });
  });
</script>
  </div>


  
</body>

</html>