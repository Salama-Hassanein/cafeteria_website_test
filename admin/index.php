<?php
// Include config file
require_once '../includes/config.php';

// Check if user is logged in as admin
if (!is_admin()) {
    redirect('index.php');
}

// Get total number of products
$query1 = "SELECT COUNT(*) as total_products FROM products";
$result1 = mysqli_query($db, $query1);
$row1 = mysqli_fetch_assoc($result1);
$total_products = $row1['total_products'];

// Get total number of categories
$query2 = "SELECT COUNT(*) as total_categories FROM categories";
$result2 = mysqli_query($db, $query2);
$row2 = mysqli_fetch_assoc($result2);
$total_categories = $row2['total_categories'];

// Get total number of users
$query3 = "SELECT COUNT(*) as total_users FROM users";
$result3 = mysqli_query($db, $query3);
$row3 = mysqli_fetch_assoc($result3);
$total_users = $row3['total_users'];

// Get total number of orders
$query4 = "SELECT COUNT(*) as total_orders FROM orders";
$result4 = mysqli_query($db, $query4);
$row4 = mysqli_fetch_assoc($result4);
$total_orders = $row4['total_orders'];

// Display admin dashboard
include_once '../includes/header.php';
?>
<div class="container">
    <h1 class="text-center">Admin Dashboard</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Total Products</h3>
                </div>
                <div class="card-body">
                    <h2><?php echo $total_products; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Total Categories</h3>
                </div>
                <div class="card-body">
                    <h2><?php echo $total_categories; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Total Users</h3>
                </div>
                <div class="card-body">
                    <h2><?php echo $total_users; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Total Orders</h3>
                </div>
                <div class="card-body">
                    <h2><?php echo $total_orders; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>