<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize form inputs
function sanitize_input($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs and sanitize them
    $fullName = sanitize_input($_POST['fullName']);
    $email = sanitize_input($_POST['email']);
    $farmLocation = sanitize_input($_POST['farmlocation']);
    $products = sanitize_input($_POST['products']);

    // Check if any fields are empty
    if (empty($fullName) || empty($email) || empty($farmLocation) || empty($products)) {
        echo "All fields are required!";
    } else {
        // Insert the vendor data into the database
        $sql = "INSERT INTO vendors (full_name, email, farm_location, products) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullName, $email, $farmLocation, $products);

        if ($stmt->execute()) {
            // Redirect to a confirmation page on success
            header("Location: vendor_confirmation.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>