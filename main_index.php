<?php
// Start a session to track user login status
session_start();

// Connect to the database
include 'config.php'; // Your database connection

// Fetch all products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Fetch three distinct reviews from unique users
$reviewsQuery = "
    SELECT u.username, r.comment, r.rating
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    GROUP BY r.user_id
    ORDER BY r.created_at DESC
    LIMIT 3
";
$reviewsResult = mysqli_query($conn, $reviewsQuery);
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
            <form action="search_results.php" method="GET" class="search-form">
                <!-- Changed action to search_results.php -->
                <div class="search-bar-container">
                    <input type="text" name="query" placeholder="Search..." class="search-bar" />
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>







            <div class="reviews">
                <h2 class="reviews-title">REVIEWS & BLOGS</h2>
                <?php
                // Loop through the reviews and display them
                while ($review = mysqli_fetch_assoc($reviewsResult)) {
                    ?>
                    <div class="review">
                        <strong><?php echo $review['username']; ?>:</strong> <?php echo $review['comment']; ?>
                        <span
                            class="stars"><?php echo str_repeat('★', $review['rating']); ?><?php echo str_repeat('☆', 5 - $review['rating']); ?></span>
                    </div>
                <?php } ?>
            </div>
        </aside>
    </div>

    <?php include_once('footer.php'); ?>

    <script>
        // Additional scripts can go here
    </script>
</body>

</html>