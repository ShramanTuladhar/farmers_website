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
    <?php
    include_once('header.php');
    ?>

    <div class="main-content">
        <?php
        include_once('left.php');
        ?>

        <section class="center-content">
            <h1>Our Products</h1>
            <div class="product-grid">
                <div class="product-card">
                    <img src="1.jpeg" alt="Product 1">
                    <h2>Product Name 1</h2>
                    <p>Description of product 1. This is a great product that you will love!</p>
                    <p class="price">$19.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="2.jpeg" alt="Product 2">
                    <h2>Product Name 2</h2>
                    <p>Description of product 2. This product is very popular among customers!</p>
                    <p class="price">$29.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="3.jpeg" alt="Product 3">
                    <h2>Product Name 3</h2>
                    <p>Description of product 3. Don't miss out on this fantastic deal!</p>
                    <p class="price">$24.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="4.jpeg" alt="Product 4">
                    <h2>Product Name 4</h2>
                    <p>Description of product 4. A must-have for everyone!</p>
                    <p class="price">$34.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </section>

    </div>
    <?php
    include_once('footer.php');
    ?>
    <script src="script.js"></script>
</body>

</html>