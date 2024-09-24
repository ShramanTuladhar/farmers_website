<?php
// Start a session to track user login status
session_start();

// Connect to the database
include 'config.php'; // Your database connection

// Fetch all products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpage Sketch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left1.php'); ?>

        <section class="center-content">
            <img src="farmers.jpeg" alt="Large Central Image" class="large-image">
            <div class="features">

                <h2>Featured Products</h2>
                <div class="featured-products">

                    <?php
                    // Loop through the products and display them
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="product-card">
                            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                            <h2><?php echo $row['name']; ?></h2>
                            <p><?php echo $row['description']; ?></p>
                            <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
                            <!-- Using JavaScript to redirect when button is clicked -->
                            <button class="add-to-cart"
                                onclick="window.location.href='add_to_cart.php?id=<?php echo $row['id']; ?>'">
                                Add to Cart
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <aside class="right-sidebar">
            <form action="/search" method="GET" style="margin-bottom: 20px; display: flex; align-items: center;">
                <input type="text" name="query" placeholder="Search..." class="search-bar"
                    style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-right: 5px;">
                <button type="submit"
                    style="padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">🔍</button>
            </form>

            <div class="reviews">
                <h2 class="reviews-title">REVIEWS & BLOGS</h2>
                <div class="review"><strong>John:</strong> Great products..really loved to talk to the producers
                    directly without needing to go to the wholesalers <span class="stars">★★★★★</span>
                </div>
                <div class="review"><strong>Jane:</strong> Pretty good service! <span class="stars">★★★★☆</span>
                </div>
                <div class="review"><strong>Kanchan:</strong> Been shopping here for a while and realized these are
                    actually the best products I have ever received from any markets just love it <span
                        class="stars">★★★★★</span></div>
            </div>
        </aside>
    </div>

    <?php include_once('footer.php'); ?>

    <script>
        // Additional scripts can go here
    </script>
</body>

</html>