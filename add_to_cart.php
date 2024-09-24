<?php
session_start();
include 'config.php'; // Your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid product!";
    exit;
}

// Handle form submission to add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = intval($_POST['quantity']);

    // Check if the product is already in the cart and calculate total cart quantity
    $cart_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] : 0;
    $total_quantity = $cart_quantity + $quantity;

    // Ensure total quantity (cart + new) doesn't exceed stock
    if ($total_quantity > $product['stock']) {
        $error = "Total quantity in cart exceeds available stock!";
    } else {
        // Add or update item in the session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Update quantity if already in the cart, otherwise add a new entry
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'stock' => $product['stock'],
                'image_path' => $product['image_path']
            ];
        }

        // Insert into the database cart table
        $user_id = $_SESSION['user_id']; // Assuming you store user ID in session
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity, price, stock) 
                         VALUES (?, ?, ?, ?, ?) 
                         ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiids", $user_id, $product_id, $quantity, $product['price'], $product['stock']);
        $stmt->execute();

        // Redirect to the cart page
        header("Location: cart.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add to Cart Section */
        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            width: 60%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .product-details img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .product-details h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .product-details p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 15px;
        }

        .product-details .price {
            font-size: 1.5em;
            color: #d9534f;
            margin-bottom: 20px;
        }

        /* Form styling for the Add to Cart button and quantity */
        form {
            display: flex;
            align-items: center;
        }

        form label {
            font-size: 1.1em;
            margin-right: 10px;
        }

        form input[type="number"] {
            width: 60px;
            padding: 5px;
            font-size: 1.1em;
            margin-right: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        button.add-to-cart-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.add-to-cart-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <!-- Include the header -->
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <!-- Include the left sidebar -->
        <?php include_once('left1.php'); ?>

        <section class="center-content">
            <h2>Add to Cart</h2>

            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>

            <div class="product-details">
                <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>"
                    class="product-image">
                <h2><?php echo $product['name']; ?></h2>
                <p><?php echo $product['description']; ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <p>Available Stock: <?php echo $product['stock']; ?></p>

                <form method="POST" action="">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" min="1" max="<?php echo $product['stock'] - $cart_quantity; ?>"
                        value="1" required>
                    <button type="submit" class="add-to-cart-button">Add to Cart</button>
                </form>
            </div>
        </section>
    </div>
    <!-- Include the footer -->
    <?php include_once('footer.php'); ?>

</body>

</html>