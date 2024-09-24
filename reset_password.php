<?php
session_start();
include 'config.php'; // Your database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $query = "SELECT * FROM users WHERE reset_token = ? AND token_created_at > NOW() - INTERVAL 1 HOUR";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (isset($_POST['reset'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Update the user's password
            $update_query = "UPDATE users SET password = ?, reset_token = NULL, token_created_at = NULL WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $new_password, $user['id']);
            $update_stmt->execute();

            echo "Password has been reset. You can now log in.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
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
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            /* Ensure padding doesn't affect total width */
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
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>

</html>