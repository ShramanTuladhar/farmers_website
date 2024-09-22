<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli('localhost', 'root', '', 'user_database', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $farmName = $_POST['farmName'];
    $products = $_POST['products'];

    // Insert the vendor data into the database
    $sql = "INSERT INTO vendors (full_name, email, farm_name, products) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullName, $email, $farmName, $products);

    if ($stmt->execute()) {
        echo "Registration as vendor successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
