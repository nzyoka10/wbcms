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
    <title>WBCM | Account Billing</title>

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
                <h4 class="text-secondary"><strong>WBCM</strong>&nbsp;&nbsp;-&nbsp;&nbsp;Listing of Account Billing</h4>
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

                    <div class="form-group pull-right">
                        <input type="text" class="search form-control" placeholder="Search by Name, Meter ID...">
                    </div>

                    <span class="counter pull-right"></span>
                    <table class="table table-hover table-bordered results">
                        <thead>
                            <tr>
                                <th>Sn#</th>
                                <th class="col-md-2 col-xs-3">Invoice Id</th>
                                <th class="col-md-2 col-xs-3">Account</th>
                                <th class="col-md-3 col-xs-3">MeterID</th>
                                <th class="col-md-2 col-xs-2">Last reading</th>
                                <th class="col-md-3 col-xs-2">Recent reading</th>
                                <th class="col-md-2 col-xs-2">Total Amount</th>
                            </tr>
                            <tr class="warning no-result">
                                <td colspan="4"><i class="fa fa-warning"></i> No result</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Inv#0001</td>
                                <td>Test User</td>
                                <td>1001</th>
                                <td>0</td>
                                <td>10</td>
                                <td><strong class="text-success">Kes.&nbsp;</strong>0</td>
                            </tr>
                            <!-- <tr>
                                <th scope="row">2</th>
                                <td>Burak Özkan</td>
                                <td>1002</th>
                                <td>0</td>
                                <td>15</td>
                                <td><strong class="text-success">Kes.&nbsp;</strong>0</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Egemen Özbeyli</td>
                                <td>1003</th>
                                <td>5</td>
                                <td>22</td>
                                <td><strong class="text-success">Kes.&nbsp;</strong>100</td>
                            </tr>
                            <tr>
                                <th scope="row">4</th>
                                <td>Engin Kızıl</td>
                                <td>1004</th>
                                <td>0</td>
                                <td>45</td>
                                <td><strong class="text-success">Kes.&nbsp;</strong>0</td>
                            </tr> -->
                        </tbody>
                    </table>





                </div>
            </div>

        </main>
        <!-- End Main -->

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