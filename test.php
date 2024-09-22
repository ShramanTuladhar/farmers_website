<?php
$servername = 'localhost';
$username = 'root';
$password = ''; // No password for root user
$port = 3308; // MySQL server port

// Create connection
$conn = new mysqli($servername, $username, $password, '', $port); // Empty string for database name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
