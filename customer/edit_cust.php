<?php
// require 'config.php';

// Check if form is submitted to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $meter_id = mysqli_real_escape_string($conn, $_POST['meter_id']);
    $first_reading = mysqli_real_escape_string($conn, $_POST['first_reading']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Check if username already exists for another user
    $checkUsernameSql = "SELECT * FROM users WHERE username='$name' AND id != '$id'";
    $usernameResult = mysqli_query($conn, $checkUsernameSql);
    if (mysqli_num_rows($usernameResult) > 0) {
        echo '<div class="alert alert-danger" role="alert">Username already exists. Please choose a different username.</div>';
    } else {
        // Check if meter_id already exists for another user
        $checkMeterIdSql = "SELECT * FROM users WHERE meter_id='$meter_id' AND id != '$id'";
        $meterIdResult = mysqli_query($conn, $checkMeterIdSql);
        if (mysqli_num_rows($meterIdResult) > 0) {
            echo '<div class="alert alert-danger" role="alert">Meter ID is already allocated. Please provide a different Meter ID.</div>';
        } else {
            // SQL query to update client data in 'users' table
            $sql = "UPDATE users SET username='$name', email='$email', contact='$mobile', address='$address', meter_id='$meter_id', first_reading='$first_reading', status='$status' WHERE id='$id'";

            // Execute SQL query
            if (mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-success" role="alert">Client updated successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Failed to update client: ' . mysqli_error($conn) . '</div>';
            }
        }
    }
}

// Check if ID is provided to fetch user details
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo '<div class="alert alert-danger" role="alert">User not found.</div>';
        exit;
    }
} else {
    echo '<div class="alert alert-danger" role="alert">No user ID provided.</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Client</title>
</head>
<body>
    <div class="container">


        <h2>Edit Client</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user['contact']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="meter_id">Meter ID</label>
                <input type="text" class="form-control" id="meter_id" name="meter_id" value="<?php echo $user['meter_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="first_reading">First Reading</label>
                <input type="text" class="form-control" id="first_reading" name="first_reading" value="<?php echo $user['first_reading']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" id="status" name="status" value="<?php echo $user['status']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Client</button>
        </form>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
