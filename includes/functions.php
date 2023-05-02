<?php

// Connect to database
require_once('config.php');

// Check if user is logged in
function is_logged_in() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

// Get user data by ID
function get_user_by_id($id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    return $user;
}

// Get all products
function get_all_products() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $products;
}

// Get product by ID
function get_product_by_id($id) {
    global $conn;
    $sql = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    return $product;
}

// Get all categories
function get_all_categories() {
    global $conn;
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

// Get category by ID
function get_category_by_id($id) {
    global $conn;
    $sql = "SELECT * FROM categories WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category;
}

// Add product to cart
function add_to_cart($product_id, $quantity, $notes, $room_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
            'notes' => $notes,
            'room_id' => $room_id,
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['notes'] .= " / " . $notes;
    }
}

// Remove product from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update product quantity in cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Update product notes in cart
function update_cart_notes($product_id, $notes) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['notes'] = $notes;
    }
}

// Get cart subtotal
function get_cart_subtotal() {
    $subtotal = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $product = get_product_by_id($item['product_id']);
            $subtotal += $product['price'] * $item['quantity'];
        }
    }
    return $subtotal;
}

// Function to clear the cart by removing all items
function clear_cart() {
    global $db;

    // Clear the cart items from the database
    $user_id = $_SESSION['user_id'];
    $query = "DELETE FROM cart WHERE user_id = '$user_id'";
    $result = mysqli_query($db, $query);

    // Reset the cart count to zero
    $_SESSION['cart_count'] = 0;

    // Reset the cart total to zero
    $_SESSION['cart_total'] = 0;

    // Return a success message
    return "Your cart has been cleared.";
}



// Function to get the order history for a user
function get_order_history($user_id) {
    global $db;
    // Query the database for the user's orders
$query = "SELECT * FROM orders WHERE user_id = '$user_id'";
$result = mysqli_query($db, $query);

// Create an array to store the order history
$order_history = array();

// Loop through the orders and add them to the array
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['id'];
    $order_date = $row['order_date'];
    $order_total = $row['order_total'];
    $order_status = $row['order_status'];

    // Get the products for the order
    $query2 = "SELECT * FROM order_items WHERE order_id = '$order_id'";
    $result2 = mysqli_query($db, $query2);

    // Create an array to store the products
    $order_products = array();

    // Loop through the products and add them to the array
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $product_id = $row2['product_id'];
        $product_name = $row2['product_name'];
        $product_price = $row2['product_price'];
        $product_quantity = $row2['product_quantity'];

        // Add the product to the array
        $order_products[] = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity
        );
    }

    // Add the order to the array
    $order_history[] = array(
        'id' => $order_id,
        'date' => $order_date,
        'total' => $order_total,
        'status' => $order_status,
        'products' => $order_products
    );
}

// Return the order history array
return $order_history;
}

// Function to get the order details for a specific order
function get_order_details($order_id)
{
    global $db;
    // Query the database for the order
    $query = "SELECT * FROM orders WHERE id = '$order_id'";
    $result = mysqli_query($db, $query);

    // Get the order details
    $order = mysqli_fetch_assoc($result);
    $order_date = $order['order_date'];
    $order_total = $order['order_total'];
    $order_status = $order['order_status'];

    // Get the user details
    $user_id = $order['user_id'];
    $query2 = "SELECT * FROM users WHERE id = '$user_id'";
    $result2 = mysqli_query($db, $query2);
    $user = mysqli_fetch_assoc($result2);
    $user_name = $user['name'];
    $user_email = $user['email'];
    $user_room = $user['room'];
    $user_ext = $user['ext'];

    // Get the product details
    $product_id = $order['product_id'];
    $query3 = "SELECT * FROM products WHERE id = '$product_id'";
    $result3 = mysqli_query($db, $query3);
    $product = mysqli_fetch_assoc($result3);
    $product_name = $product['name'];

    // Send email to user
    $to = $user_email;
    $subject = "Order Confirmation";
    $message = "Dear $user_name,\n\nYour order of $product_name has been received and is being processed. It will be delivered to your room ($user_room) with extension ($user_ext) as soon as possible. \n\nThank you for using our service.";

    $headers = "From: Cafeteria Website noreply@cafeteria.com\r\n";
    $headers .= "Reply-To: noreply@cafeteria.com\r\n";
    $headers .= "CC: admin@cafeteria.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";

    mail($to, $subject, $message, $headers);
    // Clear cart
    clear_cart();

    if(isset($_SESSION['user_id'])) {
        // Get user ID
        $user_id = $_SESSION['user_id'];

        // Check if cart is not empty
        if(!empty($_SESSION['cart'])) {
            // Place the order
            $order_id = place_order($user_id);

            if($order_id) {
                // Send email to user
                send_order_confirmation_email($order_id);

                // Redirect to index page with success message
                $_SESSION['success'] = "Order placed successfully.";
                header("Location: index.php");
                exit();
            } else {
                // If the order couldn't be placed, redirect back to order page with error message
                $_SESSION['error'] = "Error placing order. Please try again later.";
                header("Location: order.php");
                exit();
            }
        } else {
            // If the cart is empty, redirect to index page with error message
            $_SESSION['error'] = "Cart is empty.";
            header("Location: index.php");
            exit();
        }
    } else {
        // If the user is not logged in, redirect to login page
        $_SESSION['error'] = "Please log in to place an order.";
        header("Location: login.php");
        exit();
    }
}