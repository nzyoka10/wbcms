<?php
include("./config/functions.php");

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// Fetch the clients
$clients = fetchClients();

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    // Extract form data and provide default values if keys are not set
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $readingDate = isset($_POST['reading_date']) ? $_POST['reading_date'] : '';
    $dueDate = isset($_POST['due_date']) ? $_POST['due_date'] : '';
    $currentReading = isset($_POST['current_reading']) ? $_POST['current_reading'] : 0;
    $previousReading = isset($_POST['previous_reading']) ? $_POST['previous_reading'] : 0;
    $rate = isset($_POST['rate']) ? $_POST['rate'] : 0; // Default to 0 if not set
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Ensure rate is fetched and passed correctly
    $rate = floatval($rate);

    // Calculate the total bill
    $totalBill = ($currentReading - $previousReading) * $rate;

    // Call the billing function
    $billingResult = billClient($userId, $readingDate, $dueDate, $currentReading, $previousReading, $rate, $totalBill, $status);
    if ($billingResult) {
      $success_message = 'Client billed successfully!';
    } else {
      $error_message = 'Failed to bill client!';
    }
  } catch (Exception $e) {
    $error_message = 'An error occurred: ' . $e->getMessage();
  }
}



// Fetch billed clients
try {
  $billedClients = fetchBilledClients();
} catch (Exception $e) {
  echo "An error occurred: " . $e->getMessage();
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
        <h6 class="text-seconary">Water Billing & Customer Management System - <small class="text-success">Billing</small></h6>
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
          <a href="./client.php">
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
              <h5 class="mb-0 text-dark">Listing of Billing</h5>
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
                    <th scope="col">Client Name</th>
                    <th scope="col">Total Amount<sup class="text-success">(Kes.)</sup></th>
                    <th scope="col">Due date</th>
                    <th scope="col">Pay Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <?php if (empty($billedClients)) : ?>
                  <tr>
                    <td colspan="7" class="text-center text-danger">
                      <strong>No billing records found.</strong>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($billedClients as $index => $client): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($index + 1); ?></td>
                      <td><?php echo htmlspecialchars($client['reading_date']); ?></td>
                      <td><?php echo htmlspecialchars($client['client_name']); ?></td>
                      <td><?php echo htmlspecialchars($client['total']); ?></td>
                      <td><?php echo htmlspecialchars($client['due_date']); ?></td>
                      <td><?php echo $client['status'] == 1 ? 'Paid' : 'Pending'; ?></td>
                      <td>
                        <div class="dropdown">
                          <button class="btn btn-success btn-sm  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="viewBill.php?bill_id=<?php echo urlencode($client['bill_id']); ?>" class="btn btn-info btn-sm"><i class='fas fa-eye'></i>&nbsp;View</a></li>
                            <li><a class="dropdown-item" href="editBill.php?bill_id=<?php echo urlencode($client['bill_id']); ?>" class="btn btn-warning btn-sm"><i class='fas fa-edit text-primary'></i>&nbsp;Edit</a></li>
                            <li><a class="dropdown-item" href="delete_bill.php?bill_id=<?php echo $bill_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bill?')"><i class='fas fa-trash text-danger'></i>&nbsp;Delete</a></li>
                          </ul>
                        </div>
                      </td>




                      <!-- View Button -->


                      <!-- Edit Button -->


                      <!-- Delete Button -->
                      <!-- <form action="delete_bill.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                          <input type="hidden" name="bill_id" value="<?php echo htmlspecialchars($client['bill_id']); ?>">
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form> -->

                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                <!-- <td colspan='7' class='text-center text-danger'>
                  <strong>No clients billing found.</strong>
                </td> -->
                </tr>

                </tbody>
              </table>

            </div>
          </div>




        </div>

      </div>
    </main>
    <!-- End Main section -->
  </div>

  <!-- Create new billing modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-dark" id="staticBackdropLabel">Create New Bill</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form -->
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="row g-4">
            <input type="hidden" name="user_id" id="billClientId" value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">

            <div class="col-md-6">
              <label for="client_name" class="text-dark col-form-label">Client</label>
              <div class="col-sm-12">
                <select name="user_id" class="form-select" onchange="fetchPreviousReading(this.value)" required>
                  <option value="" disabled selected>Select Client</option>
                  <?php foreach ($clients as $client) : ?>
                    <option value="<?php echo htmlspecialchars($client['user_id']); ?>">
                      <?php echo htmlspecialchars($client['client_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <label for="readingDate" class="text-dark col-form-label">Reading date</label>
              <div class="col-sm-12">
                <input name="reading_date" id="reading_date" type="date" class="form-control" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="previous_reading" class="text-dark col-form-label">Previous reading</label>
              <div class="col-sm-12">
                <input type="number" name="previous_reading" id="previous_reading" class="form-control" placeholder="0.0" disabled>
              </div>
            </div>

            <div class="col-md-6">
              <label for="current_reading" class="text-dark col-form-label">Current reading</label>
              <div class="col-sm-12">
                <input type="number" name="current_reading" id="current_reading" class="form-control" placeholder="0.0" required oninput="updateTotalBill()">
              </div>
            </div>

            <div class="col-md-6">
              <label for="rate" class="text-dark col-form-label">Rate per m<sup>3</sup></label>
              <div class="col-sm-12">
                <input type="number" name="rate" id="rate" class="form-control" placeholder="14" disabled>
              </div>
            </div>

            <div class="col-md-6">
              <label for="total_bill" class="text-dark col-form-label">Total bill<sup class="text-success">(Kes)</sup></label>
              <div class="col-sm-12">
                <input type="number" name="total" id="total_bill" class="form-control" placeholder="0.0" value="<?php echo isset($_POST['total']) ? htmlspecialchars($_POST['total']) : ''; ?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="due_date" class="text-dark col-form-label">Due date</label>
              <div class="col-sm-12">
                <input type="date" name="due_date" id="due_date" class="form-control" placeholder="Due date" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select name="status" id="status" class="form-select" required>
                <option value="" disabled selected>Select</option>
                <option value="0" class="text-danger">Pending</option>
                <option value="1" class="text-success">Paid</option>
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

  <!-- custom js file -->
  <script>
    /**
     * Function to fetch the previous reading for a specific user.
     * Makes an AJAX request to fetch the previous reading value and updates the input field.
     * @param {number} user_id - The ID of the user whose previous reading is to be fetched.
     */
    function fetchPreviousReading(user_id) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "fetch_previous_reading.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            // Update the previous reading input with the fetched value
            document.getElementById("previous_reading").value = xhr.responseText;
            // Optionally update the rate field here if needed
          } else {
            console.error('Failed to fetch previous reading. Status:', xhr.status);
          }
        }
      };
      xhr.send("user_id=" + encodeURIComponent(user_id));
    }

    /**
     * Function to calculate and update the total bill based on the current reading and rate.
     */
    function updateTotalBill() {
      var currentReading = parseFloat(document.getElementById("current_reading").value) || 0;
      var previousReading = parseFloat(document.getElementById("previous_reading").value) || 0;
      var rate = parseFloat(document.getElementById("rate").value) || 0;

      // Calculate the total bill
      // const rate = 14;
      // var totalBill = (currentReading - previousReading) * rate;
      var totalBill = (currentReading - previousReading) * 14;

      // Update the total bill field
      document.getElementById("total_bill").value = totalBill.toFixed(2); // Two decimal places
    }
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
  <script src="js/bill.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script src="js/scripts.js"></script>
</body>

</html>