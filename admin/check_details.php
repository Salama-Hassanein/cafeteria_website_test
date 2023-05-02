<?php 
    include('includes/header.php');
    include('includes/config.php');
    include('includes/functions.php');

    // Get check ID from URL
    if (isset($_GET['id'])) {
        $check_id = $_GET['id'];
    } else {
        header('Location: checks.php');
    }

    // Get check details
    $query = "SELECT * FROM checks WHERE id = '$check_id'";
    $result = mysqli_query($db, $query);
    $check = mysqli_fetch_assoc($result);

    // Get user details
    $user_id = $check['user_id'];
    $query2 = "SELECT * FROM users WHERE id = '$user_id'";
    $result2 = mysqli_query($db, $query2);
    $user = mysqli_fetch_assoc($result2);

    // Get order details
    $order_id = $check['order_id'];
    $query3 = "SELECT * FROM orders WHERE id = '$order_id'";
    $result3 = mysqli_query($db, $query3);
    $order = mysqli_fetch_assoc($result3);

    // Get product details
    $product_id = $order['product_id'];
    $query4 = "SELECT * FROM products WHERE id = '$product_id'";
    $result4 = mysqli_query($db, $query4);
    $product = mysqli_fetch_assoc($result4);

    // Display check details
?>
<div class="container">
    <h1>Check Details</h1>
    <table class="table">
        <tbody>
            <tr>
                <th>User:</th>
                <td><?php echo $user['name']; ?></td>
            </tr>
            <tr>
                <th>Order:</th>
                <td><?php echo $product['name']; ?></td>
            </tr>
            <tr>
                <th>Price:</th>
                <td><?php echo $product['price']; ?></td>
            </tr>
            <tr>
                <th>Quantity:</th>
                <td><?php echo $order['quantity']; ?></td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><?php echo $check['total']; ?></td>
            </tr>
            <tr>
                <th>Date:</th>
                <td><?php echo $check['created_at']; ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php include('includes/footer.php'); ?>