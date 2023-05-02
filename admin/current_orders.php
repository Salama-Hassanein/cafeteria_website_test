<?php
// Include config file
require_once '../includes/config.php';

// Redirect to login page if not logged in
if (!is_logged_in()) {
    redirect('login.php');
}

// Check if user is an admin
if (!is_admin()) {
    redirect('index.php');
}

// Get current orders
$query = "SELECT * FROM orders WHERE status != 'completed' ORDER BY created_at DESC";
$result = mysqli_query($db, $query);

// Include header
include_once('../includes/header.php');
?>

<div class="container my-5">
    <h2>Current Orders</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Room No</th>
                <th>Ext</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($result)) { ?>
            <?php $user_id = $order['user_id']; ?>
            <?php $query2 = "SELECT * FROM users WHERE id = '$user_id'"; ?>
            <?php $result2 = mysqli_query($db, $query2); ?>
            <?php $user = mysqli_fetch_assoc($result2); ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['room']; ?></td>
                <td><?php echo $user['ext']; ?></td>
                <td><?php echo $order['total_price']; ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
                <td><a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">View
                        Details</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
// Include footer
include_once('../includes/footer.php');
?>