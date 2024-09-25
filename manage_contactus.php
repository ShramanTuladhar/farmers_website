<?php
session_start();
include 'config.php'; // Your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle deleting a contact message
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM contact_us WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: manage_contactus.php");
    exit;
}

// Fetch all contact messages
$query = "SELECT * FROM contact_us ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact Us</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* General table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        /* Table header styles */
        table thead th {
            background-color: #f2f2f2;
            padding: 12px;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
        }

        /* Table row styles */
        table tbody td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        /* Style for the message field */
        table tbody td.message-cell {
            max-width: 300px;
            /* Set max width for better readability */
            word-wrap: break-word;
            /* Break long words to fit inside the cell */
            white-space: pre-wrap;
            /* Maintain spacing and line breaks in message content */
            padding: 10px;
            /* Add extra padding for readability */
        }

        /* Back to dashboard button */
        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .back-btn i {
            margin-right: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>


<body>
    <div class="container">

        <!-- Back to Dashboard Button -->
        <button class="back-btn" onclick="window.location.href='admin_dashboard.php'">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>

        <!-- Contact Us Messages Table -->

        <h2>Manage Contact Us Messages</h2>
        <table class="contact-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                        <td>
                            <a href="manage_contactus.php?delete_id=<?php echo $row['id']; ?>" class="action-btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this message?');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>