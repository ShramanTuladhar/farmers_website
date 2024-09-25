<?php
session_start();
include 'config.php'; // Your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle deleting a vendor request
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM vendors WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: manage_vendor_requests.php");
    exit;
}

// Fetch all vendor requests
$query = "SELECT * FROM vendors ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendor Requests</title>
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

        <!-- Vendor Requests Table -->
        <h2>Manage Vendor Requests</h2>
        <table class="vendor-requests-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Farm Location</th>
                    <th>Products Offered</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['farm_location']; ?></td>
                        <td><?php echo $row['products']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="manage_vendor_requests.php?delete_id=<?php echo $row['id']; ?>"
                                class="action-btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this vendor request?');">
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