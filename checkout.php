<?php
session_start();
include 'config.php'; // Your database connection

// Handle order placement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];
    $total_price = 0;
    $location = $_POST['address']; // Get the location from the form

    // Insert each cart item into orders table
    foreach ($cart as $product_id => $item) {
        $quantity = $item['quantity'];
        $subtotal = $item['price'] * $quantity;
        $total_price += $subtotal;

        // Insert the order into the orders table
        $insert_order = "INSERT INTO orders (user_id, product_id, quantity, total_price, order_date, status, location)
                         VALUES (?, ?, ?, ?, NOW(), 'pending', ?)";
        $stmt = $conn->prepare($insert_order);
        $stmt->bind_param("iiids", $user_id, $product_id, $quantity, $subtotal, $location);
        $stmt->execute();

        // Remove item from cart database
        $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $user_id, $product_id);
        $delete_stmt->execute();

        // Update the stock in the products table
        $update_stock_query = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_query);
        $update_stock_stmt->bind_param("ii", $quantity, $product_id);
        $update_stock_stmt->execute();
    }

    // Clear the cart from the session
    unset($_SESSION['cart']);

    // Redirect to order success page
    header("Location: order_success.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }

        .main-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #444;
        }

        h3 {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .checkout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .checkout-table th,
        .checkout-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .checkout-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .checkout-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .checkout-table tr:hover {
            background-color: #f1f1f1;
        }

        .total-price {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 10px;
            text-align: right;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        textarea {
            width: 100%;
            height: 100px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            resize: none;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <!-- Include the header -->
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left1.php'); ?>
        <section class="center-content">
            <h2>Checkout</h2>
            <form method="POST" action="checkout.php">
                <h3>Review Your Order</h3>
                <table class="checkout-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0; // Initialize total price
                        foreach ($_SESSION['cart'] as $product_id => $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total_price += $subtotal;
                            echo "<tr>
                                    <td>{$item['name']}</td>
                                    <td>{$item['quantity']}</td>
                                    <td>\${$item['price']}</td>
                                    <td>\${$subtotal}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <p class="total-price"><strong>Total Price: $<?php echo number_format($total_price, 2); ?></strong></p>
                <div>
                    <label for="address">Shipping Location:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <button type="submit">Place Order</button>
            </form>
        </section>
    </div>

    <!-- Include the footer -->
    <?php include_once('footer.php'); ?>
</body>

</html>