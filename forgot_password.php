<?php
session_start();
require 'vendor/autoload.php'; // Adjust the path if necessary
include 'config.php'; // Your database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50)); // Generate a token
        $stmt->close();

        // Store token and timestamp
        $query = "UPDATE users SET reset_token = ?, token_created_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $token, $user['id']);
        $stmt->execute();

        // Send the email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;          // Enable SMTP authentication
            $mail->Username = 'farmerdoinghisbusiness@gmail.com'; // Your email address
            $mail->Password = 'mxtl efpn nkct gxvj';  // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587;                // TCP port to connect to

            // Recipients
            $mail->setFrom('your-email@gmail.com', 'Your Name'); // Sender's email and name
            $mail->addAddress($email);        // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            // Make sure the URL here points to your reset_password.php
            $mail->Body = 'Click the link to reset your password: <a href="localhost/farmers_website/reset_password.php?token=' . $token . '">Reset Password</a>';
            $mail->AltBody = 'Click the link to reset your password: localhost/farmers_website/reset_password.php?token=' . $token;

            $mail->send();
            echo "<p class='success'>Password reset email sent.</p>";
        } catch (Exception $e) {
            echo "<p class='message'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p class='message'>Email not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        /* Basic Reset */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        /* Container for forms */
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Input fields */
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Buttons */
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Message Display */
        .message {
            text-align: center;
            color: #d9534f;
            /* Red color for error messages */
        }

        .success {
            text-align: center;
            color: #5cb85c;
            /* Green color for success messages */
        }

        /* Link Styles */
        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" name="submit">Send Reset Link</button>
    </form>
</body>

</html>