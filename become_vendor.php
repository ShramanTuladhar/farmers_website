<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Vendor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your shared CSS file -->
</head>

<body>
    <?php
    include_once('header.php'); // Include header
    ?>

    <div class="main-content">
        <?php
        include_once('left.php'); // Include left menu
        ?>

        <section class="center-content">
            <h1>Become a Vendor</h1>
            <div class="form-container">
                <form action="vendor.php" method="POST">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="farmlocation">Farm Location:</label>
                    <input type="text" id="farmlocation" name="farmlocation" required>

                    <label for="products">Products Offered:</label>
                    <textarea id="products" name="products" rows="4" required></textarea>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </section>
    </div>

    <?php
    include_once('footer.php'); // Include footer
    ?>
</body>

</html>