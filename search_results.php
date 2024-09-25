<?php
// Start a session to track user login status
session_start();

// Connect to the database
include 'config.php'; // Your database connection

// Get the search query from the URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Escape the query for safety
$searchQuery = mysqli_real_escape_string($conn, $query);

// Fetch products matching the search query
$productsQuery = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
$productsResult = mysqli_query($conn, $productsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left1.php'); ?>

        <section class="center-content">
            <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
            <div class="featured-products">
                <?php
                // Check if any products were found
                if (mysqli_num_rows($productsResult) > 0) {
                    // Loop through the products and display them
                    while ($row = mysqli_fetch_assoc($productsResult)) {
                        ?>
                        <div class="product-card">
                            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                            <h2><?php echo $row['name']; ?></h2>
                            <p><?php echo $row['description']; ?></p>
                            <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
                            <button class="add-to-cart"
                                onclick="window.location.href='add_to_cart.php?id=<?php echo $row['id']; ?>'">
                                Add to Cart
                            </button>
                        </div>
                    <?php }
                } else {
                    echo "<p>No products found matching your search.</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <?php include_once('footer.php'); ?>
</body>

</html>