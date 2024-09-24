<?php
session_start(); // Start session for login check
// Connect to the database
include 'config.php'; // Assuming this file contains your database connection

// Fetch all products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your shared CSS file -->
</head>

<body>
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left.php'); ?>

        <section class="center-content">
            <h1>Our Products</h1>
            <div class="product-grid">
                <?php
                // Loop through the products and display them
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="product-card">
                        <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                        <h2><?php echo $row['name']; ?></h2>
                        <p><?php echo $row['description']; ?></p>
                        <p class="price">$<?php echo number_format($row['price'], 2); ?></p>

                        <!-- Check if the user is logged in -->
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <!-- If logged in, add to cart functionality -->
                            <button class="add-to-cart" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                        <?php } else { ?>
                            <!-- If not logged in, show login modal -->
                            <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <?php include_once('footer.php'); ?>

    <!-- Login/Signup Modal (hidden initially) -->
    <div id="loginModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModals()">&times;</span>

            <!-- Login Form -->
            <form id="loginForm" action="login.php" method="POST" style="display: block;">
                <h2>Login</h2>
                <label for="login-username">Username:</label>
                <input type="text" id="login-username" name="username" required>
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
                <button type="submit" name='login'>Login</button>
                <p style="text-align: center;">Don't have an account? <a href="javascript:void(0);"
                        onclick="toggleModal()">Sign Up</a></p>
            </form>

            <!-- Signup Form -->
            <form id="signupForm" action="signup.php" method="POST" style="display: none;">
                <h2>Sign Up</h2>
                <label for="signup-username">Username:</label>
                <input type="text" id="signup-username" name="username" required>
                <label for="signup-email">Email:</label>
                <input type="email" id="signup-email" name="email" required>
                <label for="signup-password">Password:</label>
                <input type="password" id="signup-password" name="password" required>
                <button type="submit" name='signup'>Sign Up</button>
                <p style="text-align: center;">Already have an account? <a href="javascript:void(0);"
                        onclick="toggleModal()">Login</a></p>
            </form>
        </div>
    </div>

    <!-- Add this script for modal behavior and toggling between forms -->
    <script>

    </script>
</body>

</html>