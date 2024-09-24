<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <h2>Welcome to the Admin Dashboard</h2>
    <p>Hello, Admin!</p>

    <ul>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_orders.php">Manage Orders</a></li> <!-- Added Manage Orders -->
        <li><a href="manage_reviews.php">Manage Reviews</a></li> <!-- Added Manage Reviews -->
        <li><a href="logout1.php">Logout</a></li>
    </ul>
</body>

</html>