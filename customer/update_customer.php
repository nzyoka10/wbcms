<?php
include('connection.php');

// Check if the 'id' is set in GET request
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $query = mysqli_query($con, $sql);

    // Check if the query was successful and if data was fetched
    if ($query && mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
    } else {
        // Handle error from query execution or no user found
        $user = null;
        $error_message = "Error fetching user data or user not found.";
    }
} else {
    // Handle error if 'id' is not set
    $user = null;
    $error_message = "Invalid request. User ID is missing.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="path/to/your/css/style.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Update User Information</h2>
        <?php if ($user): ?>
            <form id="updateUser" method="POST" action="update_user.php">
                <input type="hidden" name="id" id="id" value="<?php echo $user['user_id']; ?>">
                <input type="hidden" name="trid" id="trid" value="">

                <div class="mb-3 row">
                    <label for="nameField" class="col-md-3 form-label">Full Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="nameField" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="emailField" class="col-md-3 form-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" class="form-control" id="emailField" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="mobileField" class="col-md-3 form-label">Contact&nbsp;#</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="mobileField" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="addressField" class="col-md-3 form-label">Address</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="addressField" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="meterIdField" class="col-md-3 form-label">Meter ID</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="meterIdField" name="meter_id" value="<?php echo htmlspecialchars($user['meter_id']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="statusField" class="col-md-3 form-label">Status</label>
                    <div class="col-md-9">
                        <select name="status" id="statusField" class="form-control" required>
                            <option value="inactive" <?php if ($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                            <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        <?php else: ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
