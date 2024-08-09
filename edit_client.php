<?php
// Include the functions file
include("./config/functions.php");

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fetch clients for display
$clients = getClients();

// Check if the form was submitted for client registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registerClient'])) {
    $fullName = htmlspecialchars($_POST['fullName']);
    $pNumber = htmlspecialchars($_POST['pNumber']);
    $address = htmlspecialchars($_POST['address']);
    $meterId = htmlspecialchars($_POST['meter_id']);
    $firstReading = htmlspecialchars($_POST['first_reading']);
    $status = htmlspecialchars($_POST['status']);

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
    <title>WBCM | Client Listing</title>

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
                <h6 class="text-secondary">Water Billing & Customer Management System - <small class="text-success">CLIENTS</small></h6>
            </div>
            <div class="header-right text-primary">
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Create New
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <?php if (!empty($error_message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo htmlspecialchars($error_message); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($success_message)) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo htmlspecialchars($success_message); ?>
                                </div>
                            <?php endif; ?>

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
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Click
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewClientModal" onclick="populateViewModal('<?php echo htmlspecialchars($client['client_name']); ?>', '<?php echo htmlspecialchars($client['contact_number']); ?>', '<?php echo htmlspecialchars($client['address']); ?>', '<?php echo htmlspecialchars($client['meter_number']); ?>', '<?php echo htmlspecialchars($client['meter_reading']); ?>', '<?php echo htmlspecialchars($client['status']); ?>')">
                                                                    <i class="fas fa-eye"></i> View
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editClientModal" 
                                                                onclick="populateEditModal('<?php echo urlencode($client['user_id']); ?>', 
                                                                '<?php echo htmlspecialchars($client['client_name']); ?>', 
                                                                '<?php echo htmlspecialchars($client['contact_number']); ?>', 
                                                                '<?php echo htmlspecialchars($client['address']); ?>', 
                                                                '<?php echo htmlspecialchars($client['meter_number']); ?>', 
                                                                '<?php echo htmlspecialchars($client['meter_reading']); ?>', 
                                                                '<?php echo htmlspecialchars($client['status']); ?>')">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <a href="delete_client.php?id=<?php echo urlencode($client['user_id']); ?>" class="dropdown-item text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this client?');">
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
                                            <td colspan="8" class="text-center">No clients found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Card Container -->
                </div>
            </div>
        </main>
        <!-- End Main section -->
    </div>

    <!-- Create New Client Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="registerClientForm">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="pNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="pNumber" name="pNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="meter_id" class="form-label">Meter Number</label>
                            <input type="text" class="form-control" id="meter_id" name="meter_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_reading" class="form-label">First Reading</label>
                            <input type="number" class="form-control" id="first_reading" name="first_reading" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="registerClient" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Create New Client Modal -->

    <!-- View Client Modal -->
    <div class="modal fade" id="viewClientModal" tabindex="-1" aria-labelledby="viewClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClientModalLabel">View Client Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Full Name:</strong> <span id="viewFullName"></span></li>
                        <li class="list-group-item"><strong>Phone Number:</strong> <span id="viewPhoneNumber"></span></li>
                        <li class="list-group-item"><strong>Address:</strong> <span id="viewAddress"></span></li>
                        <li class="list-group-item"><strong>Meter Number:</strong> <span id="viewMeterNumber"></span></li>
                        <li class="list-group-item"><strong>First Reading:</strong> <span id="viewFirstReading"></span></li>
                        <li class="list-group-item"><strong>Status:</strong> <span id="viewStatus"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End View Client Modal -->

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="update_client.php" method="POST" id="editClientForm">
                        <input type="hidden" id="editClientId" name="client_id">
                        <div class="mb-3">
                            <label for="editFullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editFullName" name="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhoneNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="editPhoneNumber" name="pNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="editAddress" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMeterNumber" class="form-label">Meter Number</label>
                            <input type="text" class="form-control" id="editMeterNumber" name="meter_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFirstReading" class="form-label">First Reading</label>
                            <input type="number" class="form-control" id="editFirstReading" name="first_reading" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Client Modal -->

    <!-- JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables-1.10.25.min.js"></script>
    <script>
        function populateViewModal(fullName, phoneNumber, address, meterNumber, firstReading, status) {
            document.getElementById('viewFullName').textContent = fullName;
            document.getElementById('viewPhoneNumber').textContent = phoneNumber;
            document.getElementById('viewAddress').textContent = address;
            document.getElementById('viewMeterNumber').textContent = meterNumber;
            document.getElementById('viewFirstReading').textContent = firstReading;
            document.getElementById('viewStatus').textContent = status;
        }

        function populateEditModal(id, fullName, phoneNumber, address, meterNumber, firstReading, status) {
            document.getElementById('editClientId').value = id;
            document.getElementById('editFullName').value = fullName;
            document.getElementById('editPhoneNumber').value = phoneNumber;
            document.getElementById('editAddress').value = address;
            document.getElementById('editMeterNumber').value = meterNumber;
            document.getElementById('editFirstReading').value = firstReading;
            document.getElementById('editStatus').value = status;
        }

        function openSidebar() {
            document.getElementById('sidebar').style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById('sidebar').style.width = "0";
        }
    </script>
</body>

</html>
