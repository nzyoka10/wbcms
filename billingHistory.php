<?php
// Include the functions file and establish database connection
include("./config/functions.php");

// Check if client ID is provided in the URL
if (isset($_GET['bill_id'])) {
    $client_id = intval($_GET['bill_id']);
} else {
    die('Client ID is required.');
}

try {
    // Prepare the SQL query to fetch billing history for the specific client
    $query = "SELECT b.bill_id, b.reading_date, b.due_date, b.previous_reading, b.current_reading, b.rate, b.total, b.status
              FROM tbl_billinglist b
              WHERE b.user_id = ?
              ORDER BY b.reading_date DESC"; // Ordering by most recent first

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Failed to prepare query: ' . $conn->error);
    }

    // Bind the client ID to the query
    $stmt->bind_param('i', $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all billing records for the client
    $billingHistory = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die('An error occurred: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Client Billing History</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Billing History for Client ID: <?php echo htmlspecialchars($client_name); ?></h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Reading Date</th>
                    <th>Due Date</th>
                    <th>Previous Reading</th>
                    <th>Current Reading</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Status</th>
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
                            <td><?php echo htmlspecialchars($bill['rate']); ?></td>
                            <td><?php echo htmlspecialchars($bill['total']); ?></td>
                            <td><?php echo htmlspecialchars($bill['status'] == 1 ? 'Paid' : 'Pending'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">No billing records found for this client.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="billings.php" class="btn btn-sm btn-secondary">Go Back</a>
    </div>
</body>
</html>
