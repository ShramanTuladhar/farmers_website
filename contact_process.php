<?php
// Include the database connection file
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert the data into the contact_us table
    $query = "INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $email, $message);

    if ($stmt->execute()) {
        // Redirect to confirmation page after successful submission
        header('Location: contact_confirmation.php');
        exit();
    } else {
        echo "There was an error submitting your inquiry. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>