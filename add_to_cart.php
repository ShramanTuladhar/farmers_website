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

    // Check if user has ordered this product before
    $user_id = $_SESSION['user_id']; // Assuming you store user ID in session
    $order_query = "SELECT * FROM orders WHERE user_id = ? AND product_id = ?";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("ii", $user_id, $product_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $has_ordered = $order_result->num_rows > 0;

    // Handle form submission to add to cart
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_to_cart'])) {
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
        } elseif (isset($_POST['submit_review'])) {
            // Handle review submission
            $comment = $_POST['comment'];
            $rating = intval($_POST['rating']);

            // Debugging line to check rating value
            if ($rating < 1 || $rating > 5) {
                die("Rating must be between 1 and 5. Current value: " . $rating);
            }

            // Insert review into the database
            $review_query = "INSERT INTO reviews (user_id, product_id, comment, rating) VALUES (?, ?, ?, ?)";
            $review_stmt = $conn->prepare($review_query);
            $review_stmt->bind_param("iisi", $user_id, $product_id, $comment, $rating);
            $review_stmt->execute();

            // Optionally, add a success message
            $review_success = "Review submitted successfully!";
        }

    }
    // Fetch existing reviews for the product
    $reviews_query = "SELECT * FROM reviews WHERE product_id = ?";
    $reviews_stmt = $conn->prepare($reviews_query);
    $reviews_stmt->bind_param("i", $product_id);
    $reviews_stmt->execute();
    $reviews_result = $reviews_stmt->get_result();

} else {
    echo "Invalid product!";
    exit;
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .product-details {
            flex: 2;
            background-color: #fff;
            padding: 20px;
            margin: 20px;
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

        /* Form styling */
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

        /* Review section styling */
        .review-section {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: sticky;
            top: 20px;
        }

        .review-section h3 {
            font-size: 1.5em;
        }

        .review-section form {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }

        .review-section textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .rating {
            display: flex;
            margin-bottom: 10px;
        }

        .rating input {
            display: none;
            /* Hide the radio buttons */
        }

        /* Style for stars */
        .rating label {
            font-size: 2em;
            /* Size of the stars */
            color: #ccc;
            /* Default star color */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: color 0.3s ease;
        }

        /* Highlight the selected star and all stars to the left */
        .rating label:hover,
        .rating label:hover~label {
            color: #f7d74c;
            /* Highlight color for stars on hover */
        }

        /* Highlight selected stars based on the nth-child approach */
        .rating label:nth-child(-n+1):hover,
        .rating label:nth-child(-n+1):hover~label {
            color: #f7d74c;
            /* Highlight color for hovered stars */
        }

        .rating input:checked~label {
            color: #f7d74c;
            /* Highlight color for checked stars */
        }

        .rating label:hover~label {
            color: #ccc;
            /* Reset color for stars not hovered over */
        }

        .user-review {
            margin-bottom: 15px;
        }

        .user-review .stars {
            font-size: 1.2em;
            /* Size of the stars */
            color: #ccc;
            /* Default star color */
        }

        .user-review .stars span {
            color: #f7d74c;
            /* Highlight color for filled stars */
        }
    </style>
</head>

<body>

    <!-- Include the header -->
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <!-- Include the left sidebar -->
        <?php include_once('left1.php'); ?>

        <section class="product-details">
            <h2>Add to Cart</h2>

            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>

            <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <p>Available Stock: <?php echo $product['stock']; ?></p>

            <form method="POST" action="">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" min="1" max="<?php echo $product['stock']; ?>" value="1" required>
                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
            </form>
        </section>

        <!-- PHP and HTML code remains unchanged until the review section -->
        <aside class="review-section">
            <h3>Leave a Review</h3>
            <?php if (isset($review_success)) { ?>
                <p style="color: green;"><?php echo $review_success; ?></p>
            <?php } ?>
            <?php if ($has_ordered) { ?>
                <form method="POST" action="">
                    <textarea name="comment" placeholder="Write your review here..." required></textarea>
                    <div class="rating">
                        <input type="hidden" name="rating" id="rating" required>
                        <label for="rating-1" class="star" data-value="1">★</label>
                        <label for="rating-2" class="star" data-value="2">★</label>
                        <label for="rating-3" class="star" data-value="3">★</label>
                        <label for="rating-4" class="star" data-value="4">★</label>
                        <label for="rating-5" class="star" data-value="5">★</label>
                    </div>
                    <button type="submit" name="submit_review" class="add-to-cart-button">Submit Review</button>
                </form>
            <?php } else { ?>
                <p>You must purchase this product to leave a review.</p>
            <?php } ?>
            <!-- Display user reviews -->
            <h4>User Reviews:</h4>
            <?php if ($reviews_result->num_rows > 0) {
                while ($review = $reviews_result->fetch_assoc()) { ?>
                    <div class="user-review">
                        <p><strong><?php echo htmlspecialchars($review['comment']); ?></strong></p>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $review['rating']) { ?>
                                    <span>★</span> <!-- Filled star -->
                                <?php } else { ?>
                                    <span>☆</span> <!-- Empty star -->
                                <?php }
                            } ?>
                        </div>
                        <p>Date: <?php echo htmlspecialchars($review['created_at']); ?></p>
                    </div>
                <?php }
            } else { ?>
                <p>No reviews yet for this product.</p>
            <?php } ?>

        </aside>

        <script>
            // JavaScript to set the rating value and highlight stars
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function () {
                    const ratingValue = this.getAttribute('data-value');
                    document.getElementById('rating').value = ratingValue; // Set hidden input value

                    // Highlight stars based on the rating value
                    document.querySelectorAll('.star').forEach(star => {
                        const starValue = star.getAttribute('data-value');
                        star.style.color = starValue <= ratingValue ? '#f7d74c' : '#ccc'; // Set star color
                    });
                });

                // Optional: Add hover effect for star rating
                star.addEventListener('mouseover', function () {
                    const ratingValue = this.getAttribute('data-value');
                    document.querySelectorAll('.star').forEach(star => {
                        const starValue = star.getAttribute('data-value');
                        star.style.color = starValue <= ratingValue ? '#f7d74c' : '#ccc'; // Highlight color
                    });
                });

                // Reset star colors when not hovering
                star.addEventListener('mouseleave', function () {
                    document.querySelectorAll('.star').forEach(star => {
                        const starValue = star.getAttribute('data-value');
                        star.style.color = starValue <= document.getElementById('rating').value ? '#f7d74c' : '#ccc'; // Highlight if selected
                    });
                });
            });
        </script>

</body>

</html>