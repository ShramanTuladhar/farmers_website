<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php include_once('left.php'); ?>
        <section class="contact-us">
            <h1>Contact Us</h1>
            <p>If you have any questions or comments, feel free to reach out to us using the form below, or visit us at
                our location!</p>

            <div class="contact-map-container">
                <div class="contact-form-container">
                    <h2>Contact Form</h2>

                    <!-- Form starts here -->
                    <form action="contact_process.php" method="POST" class="contact-form">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required></textarea>

                        <button type="submit">Send Message</button>
                    </form>
                </div>

                <div class="map-container">
                    <h2>Our Location</h2>
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345097994!2d144.95373531531588!3d-37.81627997975168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0b0f1d1d%3A0x5045675218ceed0!2sYour%20Company%20Name!5e0!3m2!1sen!2sus!4v1630511789355!5m2!1sen!2sus"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include_once('footer.php'); ?>
</body>

</html>