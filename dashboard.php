<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

echo "<h1>Welcome to your Dashboard, " . $_SESSION['username'] . "!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>
