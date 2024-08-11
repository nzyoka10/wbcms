<?php


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
  <title>WBCMS | Billing</title>

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
        <h6 class="text-seconary">Water Billing & Customer Management System - <small class="text-success">BILLINGS</small></h6>
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
            <span class="material-icons-outlined">group_add</span>&nbsp;&nbsp;List of Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./billings.php">
            <span class="material-icons-outlined">payments</span>&nbsp;&nbsp;Billings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="#reports.php">
            <span class="material-icons-outlined">receipt_long</span>&nbsp;&nbsp;Monthly Report
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="#settings.php">
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

        <!-- create new billing modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 text-dark" id="staticBackdropLabel">Create New Bill</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- form -->
                <form action="" class="col g-4">
                <div class="row mb-3 mt-0">
                  <label for="client_name" class="text-dark col-form-label">Client</label>
                  <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example" required>
                      <option selected>Client Name</option>
                      <option value="1">John Doe</option>
                      <option value="2">June Qkala</option>
                      <option value="3">Mars Three</option>
                    </select>
                    <!-- <input type="email" class="form-control" id="inputEmail3"> -->
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Reading date</label>
                  <div class="col-sm-12">
                    <input type="date" class="form-control" id="readingDate" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Previous reading</label>
                  <div class="col-sm-12">
                    <input type="tex" class="form-control" id="readingDate" placeholder="0.0 - from db record" disabled>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Current reading</label>
                  <div class="col-sm-12">
                    <input type="number" class="form-control" id="readingDate" placeholder="0.0" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Rate per m<sup>3</sup></label>
                  <div class="col-sm-12">
                    <input type="number" class="form-control" id="readingDate" placeholder="14" disabled>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Total bill</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" id="readingDate" placeholder="calculated value" disabled>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="readingDate" class="text-dark col-form-label">Due date</label>
                  <div class="col-sm-12">
                    <input type="date" class="form-control" id="readingDate" placeholder="Due date" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select id="status" name="status" class="form-select" required>
                    <option value="" disabled selected>Select</option>
                    <option value="inactive" class="text-danger">Pending</option>
                    <option value="active" class="text-success">Paid</option>
                  </select>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>

              
             
                   
                    
                
                </form>
              </div>
              
            </div>
          </div>
        </div>

        <div class="container mt-0">
          <!-- Card Container -->
          <div class="card">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 text-dark">Listing of Billings</h5>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class='fas fa-plus'></i>&nbsp;Create Bill
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
                    <th scope="col">Reading date</th>
                    <th scope="col">Client</th>
                    <th scope="col">Amount<sup class="text-success">(Kes.)</sup></th>
                    <th scope="col">Due date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                    <!-- <th scope="col"></th> -->
                  </tr>
                </thead>
                <tbody>

                <tr>
                  <td colspan='7' class='text-center text-danger'>No clients billing found.</td>
                </tr>
                  <?php

                  // Fetch clients and output HTML
                  // try {
                  // $clients = getClients();

                  // if (!empty($clients)) {
                  //   foreach ($clients as $index => $client) {
                  //     echo "<tr>";
                  //     echo "<th scope='row'>" . htmlspecialchars($index + 1) . "</th>";
                  //     echo "<td>" . htmlspecialchars($client['created_at']) . "</td>";
                  //     echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
                  //     echo "<td>" . htmlspecialchars($client['client_name']) . "</td>";

                  //     // echo "<td>" . htmlspecialchars($client['meter_number']) . "</td>";
                  //     // echo "<td>" . htmlspecialchars($client['meter_reading']) . "</td>";
                  //     echo "<td class='text-uppercase'><small>" . htmlspecialchars($client['status']) . "</small></td>";
                  //     echo "<td>
                  // <div class='dropdown'>
                  //     <button class='btn btn-success btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>Click</button>
                  //     <ul class='dropdown-menu'>
                  //         <li>
                  //             <button type='button' class='btn btn-sm' data-bs-toggle='modal' data-bs-target='#viewClientModal' onclick=\"populateViewModal(
                  //                 '" . htmlspecialchars($client['client_name']) . "', 
                  //                 '" . htmlspecialchars($client['contact_number']) . "', 
                  //                 '" . htmlspecialchars($client['address']) . "', 
                  //                 '" . htmlspecialchars($client['meter_number']) . "', 
                  //                 '" . htmlspecialchars($client['meter_reading']) . "', 
                  //                 '" . htmlspecialchars($client['status']) . "')\">
                  //                 <i class='fas fa-eye'></i>&nbsp;View
                  //             </button>
                  //         </li>
                  //         <li>
                  //             <button type='button' class='btn btn-sm text-primary' data-bs-toggle='modal' data-bs-target='#editClientModal' onclick=\"populateEditModal(
                  //                 '" . urlencode($client['user_id']) . "', 
                  //                 '" . htmlspecialchars($client['client_name']) . "', 
                  //                 '" . htmlspecialchars($client['contact_number']) . "', 
                  //                 '" . htmlspecialchars($client['address']) . "', 
                  //                 '" . htmlspecialchars($client['meter_number']) . "', 
                  //                 '" . htmlspecialchars($client['meter_reading']) . "', 
                  //                 '" . htmlspecialchars($client['status']) . "')\">
                  //                 <i class='fas fa-edit text-primary'></i>&nbsp;Edit
                  //             </button>
                  //         </li>
                  //         <li>
                  //             <a href='delete_client.php?id=" . urlencode($client['user_id']) . "' class='btn btn-sm text-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete this Client Record?');\">
                  //                 <i class='fas fa-trash'></i>&nbsp;Delete
                  //             </a>
                  //         </li>
                  //     </ul>
                  // </div>
                  // </td>";
                  //         echo "</tr>";
                  //       }
                  //     } else {
                  //       echo "<tr><td colspan='8' class='text-center text-danger'>No clients found.</td></tr>";
                  //     }
                  //   } catch (Exception $e) {
                  //     echo "<tr><td colspan='8' class='text-center'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                  //   }

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

  </div>


  <!-- Script files -->
  <script src="js/bill.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script src="js/scripts.js"></script>

</body>

</html>