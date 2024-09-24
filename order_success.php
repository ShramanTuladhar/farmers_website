<?php
session_start();
include 'config.php'; // Your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the cart is cleared (after order placement)
if (!empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit; // Redirect to cart if it wasn't cleared
}

// Ensure no other session-related information (like shipping address) is left behind
unset($_SESSION['shipping_address']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <!-- Include the header -->
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left1.php'); ?>
        <section class="center-content">
            <h2>Order Confirmation</h2>
            <p>Your order has been successfully placed! You will receive your items shortly.</p>
            <p>Thank you for shopping with us!</p>
            <a href="main_index.php">Continue Shopping</a>
        </section>
    </div>

    <!-- Include the footer -->
    <?php include_once('footer.php'); ?>

</body>

</html>