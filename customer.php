<?php
session_start();

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
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
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <table id="example" class="table">
              <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Meter ID</th>
                <th>Status</th>
                <th>Options</th>
              </thead>
              <tbody>
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
  <script src="js/script.js"></script>


  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUser" action="post">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  <!-- Modal to Update user -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUser">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="nameField" class="col-md-3 form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="nameField" name="name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="emailField" class="col-md-3 form-label">Email</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="emailField" name="email">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="mobileField" class="col-md-3 form-label">Mobile</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="mobileField" name="mobile">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="cityField" class="col-md-3 form-label">City</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="cityField" name="City">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
