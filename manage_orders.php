<?php
session_start();
include 'config.php'; // Your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle updating order status
if (isset($_GET['update_id']) && isset($_GET['status'])) {
    $id = intval($_GET['update_id']);
    $status = $_GET['status'] === 'completed' ? 'completed' : 'pending'; // Adjusting to enum values

    // Sanitize input
    $status = mysqli_real_escape_string($conn, $status);

    $query = "UPDATE orders SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: manage_orders.php");
    exit;
}

// Determine which orders to fetch based on the current view
$view = isset($_GET['view']) ? $_GET['view'] : 'pending'; // Default to 'pending'
if ($view === 'completed') {
    $query = "SELECT o.id, o.user_id, o.product_id, o.quantity, o.total_price, o.order_date, o.status, o.location, p.image_path, u.username 
              FROM orders AS o
              JOIN products AS p ON o.product_id = p.id
              JOIN users AS u ON o.user_id = u.id
              WHERE o.status = 'completed'";
} else {
    $query = "SELECT o.id, o.user_id, o.product_id, o.quantity, o.total_price, o.order_date, o.status, o.location, p.image_path, u.username 
              FROM orders AS o
              JOIN products AS p ON o.product_id = p.id
              JOIN users AS u ON o.user_id = u.id
              WHERE o.status = 'pending'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">

        <!-- Back to Dashboard Button -->
        <button class="back-btn" onclick="window.location.href='admin_dashboard.php'">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>

        <!-- Toggle Buttons -->
        <div class="toggle-buttons">
            <a href="manage_orders.php?view=pending"
                class="toggle-btn <?php echo ($view === 'pending') ? 'active' : ''; ?>">Pending</a>
            <a href="manage_orders.php?view=completed"
                class="toggle-btn <?php echo ($view === 'completed') ? 'active' : ''; ?>">Completed</a>
        </div>

        <!-- Order List Table -->
        <h2>Manage Orders - <?php echo ucfirst($view); ?></h2>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Location</th>
                    <?php if ($view === 'pending') { ?>
                        <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td>
                            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['product_id']; ?>"
                                style="width: 50px; height: 50px;">
                        </td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo date("Y-m-d", strtotime($row['order_date'])); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <?php if ($view === 'pending') { ?>
                            <td>
                                <a href="manage_orders.php?update_id=<?php echo $row['id']; ?>&status=completed"
                                    class="action-btn delivered-btn"
                                    onclick="return confirm('Are you sure you want to mark this order as completed?');">
                                    <i class="fas fa-check-circle" style="color: green;"></i>
                                </a>
                                <a href="manage_orders.php?update_id=<?php echo $row['id']; ?>&status=pending"
                                    class="action-btn pending-btn"
                                    onclick="return confirm('Are you sure you want to mark this order as pending?');">
                                    <i class="fas fa-times-circle" style="color: red;"></i>
                                </a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>