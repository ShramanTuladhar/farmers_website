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
            <h2>Featured Products</h2>
            <div class="featured-products">
                <div class="product-card">
                    <img src="1.jpeg" alt="Product Name 1">
                    <h2>Product Name 1</h2>
                    <p>Description of product 1. This is a great product that you will love!</p>
                    <p class="price">$19.99</p>
                    <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="2.jpeg" alt="Product Name 2">
                    <h2>Product Name 2</h2>
                    <p>Description of product 2. This product is very popular among customers!</p>
                    <p class="price">$29.99</p>
                    <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="3.jpeg" alt="Product Name 3">
                    <h2>Product Name 3</h2>
                    <p>Description of product 3. Don't miss out on this fantastic deal!</p>
                    <p class="price">$24.99</p>
                    <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="4.jpeg" alt="Product Name 4">
                    <h2>Product Name 4</h2>
                    <p>Description of product 4. A must-have for everyone!</p>
                    <p class="price">$34.99</p>
                    <button class="add-to-cart" onclick="openLoginModal()">Add to Cart</button>
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
                    directly without needing to go to the wholesalers <span class="stars">★★★★★</span></div>
                <div class="review"><strong>Jane:</strong> Pretty good service! <span class="stars">★★★★☆</span></div>
                <div class="review"><strong>Kanchan:</strong> Been shopping here for a while and realized these are
                    actually the best products I have ever received from any markets just love it <span
                        class="stars">★★★★★</span></div>
            </div>
            <div class="login-signup">
                <button class="login-button" onclick="openLoginModal()">Login</button>
                <button class="signup-button" onclick="openSignupModal()">Sign Up</button>
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
                <p style="text-align: center;">Already have an account? <a href="javascript:void(0);"
                        onclick="toggleModal()">Login</a></p>
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
                <p style="text-align: center;">Don't have an account? <a href="javascript:void(0);"
                        onclick="toggleModal()">Sign Up</a></p>
            </form>
        </div>
    </div>

    <!-- JavaScript for modal functionality -->
    <script scr="script.js">
    </script>
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
        function openLoginModal() {
            loginModal.style.display = "block";
            signupModal.style.display = "none"; // Hide signup modal
        }

    </script>
</body>

</html>