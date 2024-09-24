<?php
session_start();
include 'config.php'; // Your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch cart items from the database
$query = "SELECT c.product_id, c.quantity, p.name, p.price, p.image_path, p.stock
          FROM cart AS c
          JOIN products AS p ON c.product_id = p.id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart = []; // Initialize cart array

while ($row = $result->fetch_assoc()) {
    $cart[$row['product_id']] = [
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'image_path' => $row['image_path'],
        'stock' => $row['stock']
    ];
}

// Store cart in session
$_SESSION['cart'] = $cart; // Store current cart in session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle quantity updates or removal
    $errors = []; // Initialize errors array

    foreach ($_POST['quantities'] as $product_id => $quantity) {
        // Fetch the stock quantity from the database for each product
        $query = "SELECT stock FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $available_stock = $product['stock'];

            // Ensure the quantity doesn't exceed available stock and is not negative
            if ($quantity < 0) {
                $errors[$product_id] = "Quantity cannot be negative.";
            } elseif ($quantity > $available_stock) {
                $errors[$product_id] = "Cannot exceed available stock of {$available_stock}.";
            } else {
                // Update quantity in the database
                if ($quantity == 0) {
                    // If quantity is zero, remove item from cart
                    $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                    $delete_stmt = $conn->prepare($delete_query);
                    $delete_stmt->bind_param("ii", $user_id, $product_id);
                    $delete_stmt->execute();
                } else {
                    // Update quantity in the cart
                    $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
                    $update_stmt->execute();
                }
            }
        }
    }

    // Refresh the cart after updates
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Include the header -->
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left1.php'); ?>
        <section class="center-content">
            <h2>Your Shopping Cart</h2>

            <div class="cart-container">
                <?php if (empty($cart)) { ?>
                    <p>Your cart is empty. <a href="main_index.php">Continue shopping</a></p>
                <?php } else { ?>
                    <form method="POST" action="cart.php">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_price = 0;
                                foreach ($cart as $product_id => $item) {
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_price += $subtotal;
                                    ?>
                                    <tr>
                                        <td><img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                        </td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <input type="number" name="quantities[<?php echo $product_id; ?>]"
                                                value="<?php echo $item['quantity']; ?>" min="0">
                                            <?php if (isset($errors[$product_id])) { ?>
                                                <p style="color: red;"><?php echo $errors[$product_id]; ?></p>
                                            <?php } ?>
                                        </td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div class="cart-actions">
                            <p class="total-price">Total: $<?php echo number_format($total_price, 2); ?></p>
                            <div>
                                <button type="submit">Update Cart</button>
                                <a href="checkout.php"><button type="button">Proceed to Checkout</button></a>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </section>
    </div>

    <!-- Include the footer -->
    <?php include_once('footer.php'); ?>
</body>

</html>