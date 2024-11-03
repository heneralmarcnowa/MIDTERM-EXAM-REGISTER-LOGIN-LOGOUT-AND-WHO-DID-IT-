<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
$order = getOrderById($pdo, $order_id);

if (!$order) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Delete Order</h1>
        <p>Are you sure you want to delete this order?</p>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
        <p><strong>Order Details:</strong> <?= htmlspecialchars($order['order_details']) ?></p>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
        <form action="core/handleForms.php?order_id=<?= $order_id ?>" method="POST">
            <button type="submit" name="deleteOrderBtn">Confirm Delete</button>
        </form>
        <a href="index.php">Cancel and Back to Orders</a>
    </div>
</body>
</html>