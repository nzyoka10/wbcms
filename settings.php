<?php
// Include the database connection and the settings function
include("./config/functions.php");

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Call the update_settings function with the connection and form data
        $form_data = [
            'company_name' => $_POST['company_name'],
            'company_email' => $_POST['company_email'],
            'billing_rate' => $_POST['billing_rate'],
            'enable_notifications' => isset($_POST['enable_notifications']) ? 1 : 0,
        ];

        // Execute the update_settings function
        $update_result = update_settings($conn, $form_data);

        // Check if the update was successful
        if ($update_result) {
            // Success message
            $success_message = "Settings updated successfully!";
        } else {
            // Handle failure case
            throw new Exception('Failed to update settings.');
        }
    } catch (Exception $e) {
        // Error handling
        $error_message = 'An error occurred: ' . $e->getMessage();
    }
}

// Fetch the current settings to display in the form
$query = "SELECT * FROM tbl_settings LIMIT 1";
$result = $conn->query($query);
$settings = $result->fetch_assoc();
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WBCMS | System Settings</title>

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
                <h6 class="text-seconary">Water Billing & Customer Management System - <small class="text-success">System Settings</small></h6>
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
                <div class="container mt-2">

                    <div class="card">
                        <div class="card-body">

                            <h2 class="mb-0 text-dark">System Settings</h2>
                    

                            <!-- Success or Error Message -->
                            <?php if (isset($success_message)) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $success_message; ?>
                                </div>
                            <?php elseif (isset($error_message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Settings Form -->
                            
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4 mb-4">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($settings['company_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_email" class="form-label">Company Email</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" value="<?php echo htmlspecialchars($settings['company_email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="billing_rate" class="form-label">Billing Rate</label>
                                    <input type="number" step="0.01" class="form-control" id="billing_rate" name="billing_rate" value="<?php echo htmlspecialchars($settings['billing_rate']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="enable_notifications" name="enable_notifications" <?php echo $settings['enable_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="enable_notifications">
                                            Enable Email Notifications
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;&nbsp;Save Settings
                                </button>
                            </form>
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