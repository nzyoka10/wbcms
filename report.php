<?php
// Include the functions file and establish database connection
include("./config/functions.php");

// Fetch filter criteria if any
$filterStatus = isset($_GET['status']) ? intval($_GET['status']) : null;
$filterMonth = isset($_GET['month']) ? $_GET['month'] : null;

try {
    // Build the query
    $query = "SELECT b.bill_id, c.client_name, b.reading_date, b.due_date, b.current_reading, b.previous_reading, b.rate, b.total, b.status
              FROM tbl_billinglist b
              JOIN tbl_clients c ON b.user_id = c.user_id
              WHERE 1=1";

    if ($filterStatus !== null) {
        $query .= " AND b.status = ?";
    }

    if ($filterMonth) {
        $query .= " AND DATE_FORMAT(b.reading_date, '%Y-%m') = ?";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . $conn->error);
    }

    // Bind parameters based on the filters
    $bindTypes = '';
    $bindParams = [];

    if ($filterStatus !== null) {
        $bindTypes .= 'i';
        $bindParams[] = $filterStatus;
    }

    if ($filterMonth) {
        $bindTypes .= 's';
        $bindParams[] = $filterMonth;
    }

    if ($bindTypes) {
        $stmt->bind_param($bindTypes, ...$bindParams);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $billedAccounts = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die('An error occurred: ' . $e->getMessage());
}

// Function to generate CSV content
function generateCSV($data)
{
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Bill ID', 'Client', 'Reading Date', 'Due Date', 'Previous Reading', 'Current Reading', 'Rate', 'Total', 'Status']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Monthly_Report.csv"');
    generateCSV($billedAccounts);
    exit;
}

// Function to generate PDF content (requires FPDF library)
// function generatePDF($data)
// {
//     require('docs/fpdf/fpdf.php');
//     $pdf = new FPDF();
//     $pdf->AddPage();
//     $pdf->SetFont('Arial', 'B', 12);

//     // Table header
//     $header = ['Sn#', 'Client', 'Reading Date', 'Due Date', 'Previous Reading', 'Current Reading', 'Rate', 'Total', 'Status'];
//     foreach ($header as $col) {
//         $pdf->Cell(24, 7, $col, 1);
//     }
//     $pdf->Ln();

//     // Table data
//     $pdf->SetFont('Arial', '', 12);
//     foreach ($data as $row) {
//         $pdf->Cell(24, 6, $row['bill_id'], 1);
//         $pdf->Cell(24, 6, $row['client_name'], 1);
//         $pdf->Cell(24, 6, $row['reading_date'], 1);
//         $pdf->Cell(24, 6, $row['due_date'], 1);
//         $pdf->Cell(24, 6, $row['previous_reading'], 1);
//         $pdf->Cell(24, 6, $row['current_reading'], 1);
//         $pdf->Cell(24, 6, $row['rate'], 1);
//         $pdf->Cell(24, 6, $row['total'], 1);
//         $pdf->Cell(24, 6, $row['status'] == 1 ? 'Paid' : 'Pending', 1);
//         $pdf->Ln();
//     }
//     $pdf->Output('D', 'Monthly_Report.pdf');
// }

// if (isset($_POST['export_pdf'])) {
//     generatePDF($billedAccounts);
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WBCMS | Reports</title>

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
                <h6 class="text-seconary">Water Billing & Customer Management System - <small class="text-success">Monthly Report</small></h6>
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
      
        <!-- Main section -->
        <main class="main-container">
            <div class="row">
                <div class="container mt-0">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-dark">Generate Report</h2>

                            <!-- Filter Form -->
                            <form class="row g-3 mb-4 mt-2" method="GET" action="report.php">
                                <div class="col-md-3">
                                    <label for="month" class="form-label">Month</label>
                                    <input type="month" name="month" id="month" class="form-control" value="<?php echo htmlspecialchars($filterMonth); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">All</option>
                                        <option value="0" <?php echo $filterStatus === 0 ? 'selected' : ''; ?>>Pending</option>
                                        <option value="1" <?php echo $filterStatus === 1 ? 'selected' : ''; ?>>Paid</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-sm btn-success mt-4">
                                        <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;&nbsp;Filter
                                    </button>
                                </div>
                            </form>

                            <!-- Display Results -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sn#</th>
                                        <th>Client</th>
                                        <th>Reading Date</th>
                                        <th>Due Date</th>
                                        <th>Previous Reading</th>
                                        <th>Current Reading</th>
                                        <!-- <th>Rate</th> -->
                                        <th>Total Amount</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                               
                                    <?php if (!empty($billedAccounts)) : ?>
                                        <?php foreach ($billedAccounts as $index => $bill) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($index + 1); ?></td>                                                
                                                <td><?php echo htmlspecialchars($bill['client_name']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['reading_date']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['due_date']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['previous_reading']); ?></td>
                                                <td><?php echo htmlspecialchars($bill['current_reading']); ?></td>
                                                <!-- <td><?php echo htmlspecialchars($bill['rate']); ?></td> -->
                                                <td><?php echo htmlspecialchars($bill['total']); ?></td>
                                                <!-- <td><?php echo htmlspecialchars($bill['status'] == 1 ? 'Paid' : 'Pending'); ?></td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">
                                                <strong>No records found.</strong>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- Print, Export to CSV and PDF Buttons -->
                            <form method="POST" action="report.php">
                                <button type="button" class="btn btn-sm btn-dark me-2" onclick="printReceipt()">
                                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;&nbsp;Print PDF
                                </button>
                                <button type="submit" name="export_csv" class="btn btn-sm btn-warning me-2">
                                    <i class="fa fa-table" aria-hidden="true"></i>&nbsp;&nbsp;Export CSV
                                </button>
                                <!-- <button type="submit" name="export_pdf" class="btn btn-sm btn-warning me-2">
                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>&nbsp;&nbsp;Export PDF
                                </button> -->
                            </form>

                            <!-- Receipt Section -->
                            <div id="receipt-section" style="display:none;">
                                <div class="reaceipt-header text-center">
                                    <h3>Monthly Report</h3>
                                    <p>Water Bill</p>
                                    <p>P.O Box 0101</p>

                                </div>
                                <table class="table cell-border">
                                    <tr>
                                        <!-- <th>Bill ID</th> -->
                                        <th>Client</th>
                                        <th>Reading Date</th>
                                        <th>Due Date</th>
                                        <th>Previous Reading</th>
                                        <th>Current Reading</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                    <?php foreach ($billedAccounts as $bill) : ?>
                                        <tr>
                                            <!-- <td><?php echo htmlspecialchars($bill['bill_id']); ?></td> -->
                                            <td><?php echo htmlspecialchars($bill['client_name']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['reading_date']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['due_date']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['previous_reading']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['current_reading']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['rate']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['total']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['status'] == 1 ? 'Paid' : 'Pending'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End Main section -->
    </div>

    <!-- custom js file -->
    <script>
        function printReceipt() {
            var printContents = document.getElementById('receipt-section').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
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