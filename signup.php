<?php
// Start a session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli('localhost:3308', 'root', '', 'user_database',3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database.";
}

// Check if the form is submitted
if (isset($_POST['signup'])) {
    // Get form inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful! You can now <a href='login.html'>login</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
