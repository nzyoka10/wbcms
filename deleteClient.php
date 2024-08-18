<?php
// Include database connection and functions
include("./config/functions.php");

// Initialize variables
$error_message = '';
$success_message = '';

// Check if user_id is passed in the URL
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $userId = htmlspecialchars($_GET['user_id']);

    // Handle deletion
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            // Delete the client
            deleteClient($userId);

            // Redirect to customer.php
            header("Location: customer.php");
            exit();
        } catch (Exception $e) {
            $error_message = 'An error occurred: ' . $e->getMessage();
        }
    }

    // Fetch client details for confirmation
    $client = getClientById($userId);
    if (!$client) {
        $error_message = 'Client not found.';
    }
} else {
    $error_message = 'Invalid client ID.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WBCMS | Delete Client</title>

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
    <link rel="stylesheet" href="css/dataTables.dataTables.css">

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
                <h6 class="text-secondary">Water Billing & Customer Management System - <small class="text-success">DELETE CLIENT</small></h6>
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
                        <div class="card-body">
                            <h2 class="card-title">Delete Client Record</h2>

                            <?php if (!empty($error_message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo htmlspecialchars($error_message); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($success_message)) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo htmlspecialchars($success_message); ?>
                                </div>
                            <?php else : ?>
                                <?php if ($client) : ?>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?user_id=' . urlencode($userId); ?>">
                                        <div class="p-3 mb-2 bg-danger text-white">Are you sure you want to delete the following client?</div>
                                        <table class="table table-bordered mt-4">
                                            <tr>
                                                <th>Full Name</th>
                                                <td><?php echo htmlspecialchars($client['client_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Phone Number</th>
                                                <td><?php echo htmlspecialchars($client['contact_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td><?php echo htmlspecialchars($client['address']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Meter Number</th>
                                                <td><?php echo htmlspecialchars($client['meter_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>First Reading</th>
                                                <td><?php echo htmlspecialchars($client['meter_reading']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td><?php echo htmlspecialchars($client['status']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date Created</th>
                                                <td><?php echo htmlspecialchars($client['created_at']); ?></td>
                                            </tr>
                                        </table>
                                        <div class="d-flex justify-content-start mt-4">
                                            <a href="./client.php" class="btn btn-sm btn-danger me-2">                                          
                                                <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Go Back                               
                                            </a>
                                            <button type="submit" class="btn btn-sm btn-success me-2">
                                                <i class='fas fa-trash text-white'></i>&nbsp;&nbsp;Delete
                                            </button>
                                            
                                        </div>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
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
