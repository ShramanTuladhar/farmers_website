<?php
include 'config.php'; // Database connection

// Fetch product details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
}

if (!$product) {
    echo "Product not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <h2>Edit Product</h2>

    <!-- Edit Product Form -->
    <form action="manage_products.php" method="POST" enctype="multipart/form-data">
        <!-- Back to Dashboard Button -->
        <button class="back-btn" onclick="window.location.href='admin_dashboard.php'">
            <i class="fas fa-arrow-left"></i> Back to Manage Products
        </button>
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <label for="name">Product Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required><br>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br>

        <label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*"><br>
        <img src="<?php echo $product['image_path']; ?>" width="100" height="100"><br>

        <button type="submit" name="edit_product">Update Product</button>
    </form>
</body>

</html>