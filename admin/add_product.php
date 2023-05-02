<?php
include_once('../includes/config.php');
include_once('../includes/functions.php');

// Check if the user is logged in as admin
if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
} elseif (!is_admin()) {
    header('Location: index.php');
    exit();
}

// Get the list of categories for the form
$categories = get_categories();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $price = (float) $_POST['price'];
    $category_id = (int) $_POST['category_id'];
    $image = $_FILES['image'];

    // Check if all fields are filled in
    if (empty($name) || empty($description) || empty($price) || empty($category_id) || empty($image)) {
        $error_message = 'Please fill in all fields';
    } else {
        // Upload the image to the server
        $image_path = upload_image($image);

        // Add the product to the database
        $query = "INSERT INTO products (name, description, price, category_id, image) VALUES ('$name', '$description', $price, $category_id, '$image_path')";
        $result = mysqli_query($db, $query);

        if ($result) {
            // Redirect to the products page
            header('Location: products.php');
            exit();
        } else {
            $error_message = 'An error occurred while adding the product. Please try again later.';
        }
    }
}

// Include the header
include_once('../includes/header.php');
?>

<h1>Add Product</h1>

<?php if (isset($error_message)): ?>
<div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" id="description" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" class="form-control">
    </div>
    <div class="form-group">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" class="form-control">
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Add Product</button>
</form>

<?php
// Include the footer
include_once('../includes/footer.php');
?>