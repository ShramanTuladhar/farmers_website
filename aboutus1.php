<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <?php include_once('header.php'); ?>

    <div class="main-content">
        <?php
        include_once('left.php');
        ?>
        <section class="about-us">
            <h1>About Us</h1>
            <p>Welcome to [Your Company Name], where we connect consumers directly with local farmers and producers. Our
                goal is to provide fresh, high-quality products while supporting sustainable farming practices.</p>

            <h2>Our Mission</h2>
            <p>To empower local farmers and provide consumers with access to fresh and healthy products, promoting
                sustainable agriculture and community well-being.</p>

            <h2>Our Values</h2>
            <ul>
                <li><strong>Sustainability:</strong> We prioritize eco-friendly practices that benefit both the
                    environment and the community.</li>
                <li><strong>Quality:</strong> We ensure that all our products meet high standards of quality and
                    freshness.</li>
                <li><strong>Community:</strong> We support local farmers and strive to foster strong community
                    relationships.</li>
            </ul>

            <h2>Meet Our Team</h2>
            <p>Our dedicated team is passionate about promoting local agriculture and connecting you with the best
                products. We believe in transparency and are here to answer any questions you may have.</p>
            <div class="team-members">
                <div class="team-member">
                    <h3>Jane Doe</h3>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-member">
                    <h3>John Smith</h3>
                    <p>Head of Operations</p>
                </div>
                <div class="team-member">
                    <h3>Emily Johnson</h3>
                    <p>Marketing Manager</p>
                </div>
            </div>
        </section>
    </div>

    <?php include_once('footer.php'); ?>
</body>

</html>