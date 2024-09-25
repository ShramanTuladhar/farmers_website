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

                        <button class="add-to-cart" onclick="redirectToLogin()">Add to Cart</button>

                    </div>
                <?php } ?>  
            </div>
        </section>
    </div>

    <?php include_once('footer.php'); ?>

    <script>
        // Redirect to login page with an alert message
        function redirectToLogin() {
            alert("You need to login first.");
            window.location.href = "index.php"; // Redirect to the index or login page
        }

        // Remove the addToCart function as it's no longer needed
    </script>
</body>

</html>