<header class="header">
    <div class="top-row">
        <div class="logo">
            <img src="farmer_logo.png" alt="Farmer Logo" style="max-height: 100px;">
        </div>

        <h1 class="slogan">Harvesting Quality, Cultivating Community</h1>

        <div class="cart-icon">
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) { ?>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart" style="font-size: 30px;"></i>
                </a>
            <?php } else { ?>
                <i class="fas fa-shopping-cart" style="font-size: 30px; cursor: not-allowed;"></i>
            <?php } ?>
        </div>
    </div>
</header>