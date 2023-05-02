<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once '../includes/config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form
    $check_num = mysqli_real_escape_string($db, $_POST['check_num']);
    $order_id = mysqli_real_escape_string($db, $_POST['order_id']);
    $total = mysqli_real_escape_string($db, $_POST['total']);
    $date = date('Y-m-d H:i:s');

    // Insert data into checks table
    $query = "INSERT INTO checks (check_num, order_id, total, date) VALUES ('$check_num', '$order_id', '$total', '$date')";
    mysqli_query($db, $query);

    // Redirect to current orders page
    header('Location: current_orders.php');
    exit;
}

// Get current orders from database
$query = "SELECT * FROM orders WHERE status = 'pending'";
$result = mysqli_query($db, $query);

// Include header and navigation bar
require_once 'header.php';
?>

<div class="container">
    <h1 class="my-4">Checks</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="order_id">Order ID:</label>
            <select class="form-control" name="order_id" required>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="check_num">Check Number:</label>
            <input type="text" class="form-control" name="check_num" required>
        </div>
        <div class="form-group">
            <label for="total">Total:</label>
            <input type="number" step="0.01" min="0" class="form-control" name="total" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Check</button>
    </form>
</div>

<?php
// Include footer
require_once 'footer.php';
?>