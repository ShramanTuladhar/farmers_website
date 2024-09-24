<?php
include 'config.php';

$username = "admin";
$email = "admin@example.com";
$password = "admin123"; // You can change this to the password you want

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the admin user into the database
$query = "INSERT INTO admin_users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
if (mysqli_query($conn, $query)) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
