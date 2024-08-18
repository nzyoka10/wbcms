<?php
// Include the functions file and establish database connection
include("./config/functions.php");

// Initialize error and success messages
$error_message = '';
$success_message = '';

if (isset($_GET['bill_id'])) { // Corrected to use user_id
    $client_id = intval($_GET['bill_id']);
    // $client_name = $_GET['client_name'];

    try {
        // Fetch client name
        $stmt = $conn->prepare("SELECT client_name FROM tbl_clients WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception('Failed to prepare client query: ' . $conn->error);
        }
        $stmt->bind_param('i', $client_id);
        $stmt->execute();
        $stmt->bind_result($client_name);
        $stmt->fetch();
        $stmt->close();

        if (empty($client_name)) {
            $error_message = 'Client not found.';
            // die('Client not found.');
        }

        // Fetch billing history
        $billingHistory = getBillingHistory($client_id, $conn);
    } catch (Exception $e) {
        die('An error occurred: ' . $e->getMessage());
    }
} else {
    $error_message = 'Client ID is required.';
    // die('Client ID is required.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WBCMS | Billing History</title>

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
                    <div class="card">
                        <div class="card-body">

                            <h4>Billing History for Client:
                                <?php echo htmlspecialchars($client_name); ?>
                            </h4>

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Sn#</th>
                                        <th>Reading Date</th>
                                        <th>Due Date</th>
                                        <th>Previous Reading</th>
                                        <th>Current Reading</th>
                                        <!-- <th>Rate</th> -->
                                        <th>Total Amount</th>
                                        <th>Pay Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($billingHistory)) : ?>
                                        <?php foreach ($billingHistory as $bill) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($bill['bill_id']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['reading_date']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['due_date']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['previous_reading']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['current_reading']); ?></td>
                                                <!-- <td><?php echo htmlspecialchars($bill['rate']); ?></td> -->
                                                <td><?php echo htmlspecialchars($bill['total']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['status'] == 1 ? 'Paid' : 'Pending'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                <strong>No billing records found for this client.</strong>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-start">
                                <a href="viewBill.php?bill_id=<?php echo urlencode($client_id); ?>" class="btn btn-sm btn-danger mt-2 me-2">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Go Back
                                </a>
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