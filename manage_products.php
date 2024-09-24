<?php
session_start();
include 'config.php'; // Your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle adding a product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_path = 'uploads/' . basename($_FILES['image']['name']);

    // Upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        $query = "INSERT INTO products (name, description, price, stock, image_path) VALUES ('$name', '$description', '$price', '$stock', '$image_path')";
        mysqli_query($conn, $query);
    }
}

// Handle deleting a product
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: manage_products.php");
    exit;
}

// Handle editing a product
if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_path = 'uploads/' . basename($_FILES['image']['name']);

    // Update product info
    if (!empty($_FILES['image']['name'])) {
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', image_path='$image_path' WHERE id='$id'";
    } else {
        $query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock' WHERE id='$id'";
    }
    mysqli_query($conn, $query);
    header("Location: manage_products.php");
    exit;
}

// Fetch all products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Manage Products</h2>

        <!-- Back to Dashboard Button -->
        <button class="back-btn" onclick="window.location.href='admin_dashboard.php'">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>

        <!-- Button to Add Product -->
        <button class="add-product-btn" onclick="document.getElementById('add-product-form').style.display='block'">
            <i class="fas fa-plus"></i> Add Product
        </button>

        <!-- Add Product Form -->
        <div id="add-product-form" style="display:none;">
            <form action="manage_products.php" method="POST" enctype="multipart/form-data" class="product-form">
                <label for="name">Product Name:</label>
                <input type="text" name="name" required><br>

                <label for="description">Description:</label>
                <textarea name="description" required></textarea><br>

                <label for="price">Price:</label>
                <input type="number" name="price" step="0.01" required><br>

                <label for="stock">Stock:</label>
                <input type="number" name="stock" required><br>

                <label for="image">Product Image:</label>
                <input type="file" name="image" accept="image/*"><br>

                <button type="submit" name="add_product" class="submit-btn">Add Product</button>
            </form>
        </div>

        <!-- Product List Table -->
        <h3>Products</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><img src="<?php echo $row['image_path']; ?>" alt="Product Image" width="50" height="50"></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="manage_products.php?delete_id=<?php echo $row['id']; ?>" class="action-btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>


</html>