<?php
// Start a session
session_start();

// Show error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['login'])) {
    // Get form inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables to track user login status
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_logged_in'] = true; // Set a flag for login status

            // Redirect to the main page after login
            header("Location: main_index.php");
            exit; // Always exit after a redirect
        } else {
            // If password is incorrect, store the error in the session
            $_SESSION['error'] = "Invalid password. Please try again.";
            header("Location: index.php"); // Redirect back to the login page
            exit;
        }
    } else {
        // If username is not found, store the error in the session
        $_SESSION['error'] = "No user found with that username.";
        header("Location: index.php"); // Redirect back to the login page
        exit;
    }
    $stmt->close();
}
$conn->close();
?>