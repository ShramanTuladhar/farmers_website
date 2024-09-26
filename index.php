<?php
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
        <?php include_once('left.php'); ?>

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
                            <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>

        <aside class="right-sidebar">
            <form action="index.php" method="GET" class="search-form">
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
            
            <!-- Login and Signup Buttons -->
            <div class="auth-buttons">
                <button onclick="openLoginModal()" class="btn">Login</button>
                <button onclick="openSignupModal()" class="btn">Sign Up</button>
            </div>
        </aside>
    </div>

    <?php include_once('footer.php'); ?>

    <!-- Signup Modal -->
    <div id="signupModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModals()">&times;</span>
            <form action="signup.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name='signup'>Sign Up</button>
            </form>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="closeModals()">&times;</span>
            <form action="login.php" method="POST">
                <label for="login-username">Username:</label>
                <input type="text" id="login-username" name="username" required>
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
                <button type="submit" name='login'>Login</button>
                <p style="text-align: center;">
                    <a href="forgot_password.php" style="color: #007BFF; text-decoration: underline;">Forgot
                        Password?</a>
                </p>
                <p style="text-align: center;">
                    Don't have an account?
                    <a href="javascript:void(0);" onclick="toggleModal()">Sign Up</a>
                </p>
            </form>
        </div>
    </div>

    <!-- JavaScript for modal functionality -->
    <script src="script.js"></script>
    <script>
        var signupModal = document.getElementById("signupModal");
        var loginModal = document.getElementById("loginModal");

        function openSignupModal() {
            signupModal.style.display = "block";
            loginModal.style.display = "none"; // Hide login modal
        }

        function openLoginModal() {
            loginModal.style.display = "block";
            signupModal.style.display = "none"; // Hide signup modal
        }

        function closeModals() {
            signupModal.style.display = "none";
            loginModal.style.display = "none";
        }

        function toggleModal() {
            if (signupModal.style.display === "block") {
                closeModals();
                openLoginModal();
            } else {
                closeModals();
                openSignupModal();
            }
        }

        // Close modals when clicking outside
        window.onclick = function (event) {
            if (event.target == signupModal || event.target == loginModal) {
                closeModals();
            }
        }
    </script>
</body>

</html>
