<header class="header">
    <div class="top-row">
        <div class="logo">
            <img src="farmer_logo.png" alt="Farmer Logo" style="max-height: 100px;">
        </div>

        <h1 class="slogan">Harvesting Quality, Cultivating Community</h1>

        <div class="cart-icon">
            <?php
            if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                include_once('config.php');
                // Fetch user email using session user ID or username
                $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
                $sql = "SELECT email FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->bind_result($email);
                $stmt->fetch();
                $stmt->close();
                $conn->close();
                ?>
                <div class="user-info">
                    <i class="fas fa-user" style="font-size: 30px;"></i>
                    <span class="user-email"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart" style="font-size: 30px;"></i>
                </a>
            <?php } else { ?>
                <i class="fas fa-shopping-cart" style="font-size: 30px; cursor: not-allowed;"></i>
            <?php } ?>
        </div>
    </div>
</header>