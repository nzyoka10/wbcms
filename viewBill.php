<?php
// Include the functions file
include("./config/functions.php");

// Check if bill_id is provided
if (!isset($_GET['bill_id']) || empty($_GET['bill_id'])) {
    die('Bill ID not provided.');
}

// Fetch bill_id from query parameters and sanitize it
$bill_id = intval($_GET['bill_id']);

try {
    // Prepare and execute the query
    $query = "SELECT b.bill_id, c.client_name, b.reading_date, b.due_date, b.current_reading, b.previous_reading, b.rate, b.total, b.status
              FROM tbl_billinglist b
              JOIN tbl_clients c ON b.user_id = c.user_id
              WHERE b.bill_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $bill_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die('Bill not found.');
    }

    $bill = $result->fetch_assoc();
} catch (Exception $e) {
    die('An error occurred: ' . $e->getMessage());
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
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3 text-dark">Billing Details</h3>

                            <table class="table table-bordered">
                                <tr class="col">
                                    <th>Client</th>
                                    <td><?php echo htmlspecialchars($bill['client_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Reading Date</th>
                                    <td><?php echo htmlspecialchars($bill['reading_date']); ?></td>
                                </tr>
                                <tr>
                                    <th>Due Date</th>
                                    <td><?php echo htmlspecialchars($bill['due_date']); ?></td>
                                </tr>
                                <tr>
                                    <th>Previous Reading</th>
                                    <td><?php echo htmlspecialchars($bill['previous_reading']); ?></td>
                                </tr>
                                <tr>
                                    <th>Current Reading</th>
                                    <td><?php echo htmlspecialchars($bill['current_reading']); ?></td>
                                </tr>
                                <tr>
                                    <th>Rate per m<sup>3</sup></th>
                                    <td><?php echo htmlspecialchars($bill['rate']); ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><?php echo htmlspecialchars($bill['total']); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo $bill['status'] == 0 ? 'Paid' : 'Pending'; ?></td>
                                </tr>
                            </table>
                            <div class="d-flex justify-content-start">
                                <a href="billings.php" class="btn btn-sm btn-danger me-2">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Go Back
                                </a>
                                
                                <a href="editBill.php?bill_id=<?php echo $bill_id; ?>" class="btn btn-sm btn-dark me-2">
                                    <i class='fas fa-edit text-white'></i>&nbsp;Edit Record
                                </a>

                                <a href="billingHistory.php?bill_id=<?php echo $bill_id; ?>" class="btn btn-sm btn-warning me-2">
                                    <i class="fa fa-history" aria-hidden="true"></i>&nbsp;Billing History
                                </a>

                                <!-- <a href="billings.php" class="btn btn-sm btn-danger me-2">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Go Back
                                </a> -->

                                <!-- <a href="delete_bill.php?bill_id=<?php echo $bill_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bill?')">
                                    <i class='fas fa-trash'></i>&nbsp;
                                    Delete
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End Main section -->
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/bill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>