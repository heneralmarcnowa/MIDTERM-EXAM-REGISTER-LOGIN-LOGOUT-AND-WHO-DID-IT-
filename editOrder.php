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
$shops = getAllShops($pdo);
$customers = getAllCustomers($pdo);

if (!$order) {
    header("Location: index.php");
    exit();
}

$orderDetails = explode(', ', $order['order_details']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function addOrderDetail() {
            const container = document.getElementById('orderDetailsContainer');
            const orderDetail = document.createElement('div');
            orderDetail.className = 'order-detail';
            orderDetail.innerHTML = `
                <select name="orderDetails[]" required>
                    <option value="" disabled selected>Select Coffee</option>
                    <optgroup label="Hot Coffee">
                        <option value="Hot Espresso: ₱80.00">Espresso: ₱80.00</option>
                        <option value="Hot Double Espresso: ₱95.00">Double Espresso: ₱95.00</option>
                        <option value="Hot Latte: ₱95.00">Latte: ₱95.00</option>
                        <option value="Hot Cappuccino: ₱95.00">Cappuccino: ₱95.00</option>
                        <option value="Hot Macchiato: ₱100.00">Macchiato: ₱100.00</option>
                    </optgroup>
                    <optgroup label="Cold Coffee">
                        <option value="Cold Espresso: ₱90.00">Espresso: ₱90.00</option>
                        <option value="Cold Double Espresso: ₱105.00">Double Espresso: ₱105.00</option>
                        <option value="Cold Latte: ₱105.00">Latte: ₱105.00</option>
                        <option value="Cold Cappuccino: ₱105.00">Cappuccino: ₱105.00</option>
                        <option value="Cold Macchiato: ₱105.00">Macchiato: ₱105.00</option>
                    </optgroup>
                    <optgroup label="Non-Coffee">
                        <option value="Hot Chocolate: ₱80.00">Hot Chocolate: ₱80.00</option>
                        <option value="Matcha: ₱95.00">Matcha: ₱95.00</option>
                        <option value="Milkshake: ₱95.00">Milkshake: ₱95.00</option>
                        <option value="Smoothie: ₱95.00">Smoothie: ₱95.00</option>
                    </optgroup>
                </select>
                <input type="number" name="quantities[]" min="1" placeholder="Quantity" required>
                <button type="button" class="remove-button" onclick="removeOrderDetail(this)">Remove</button>
            `;
            container.appendChild(orderDetail);
            updateRemoveButtons();
        }

        function removeOrderDetail(button) {
            const orderDetail = button.parentElement;
            orderDetail.remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const orderDetails = document.querySelectorAll('.order-detail');
            const removeButtons = document.querySelectorAll('.remove-button');
            removeButtons.forEach(button => button.style.display = (orderDetails.length > 1) ? 'inline-block' : 'none');
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit Order</h1>
        <form action="core/handleForms.php?order_id=<?= $order_id ?>" method="POST">
            <label for="shopId">Shop Location</label>
            <select id="shopId" name="shopId" required>
                <?php foreach ($shops as $shop): ?>
                    <option value="<?= htmlspecialchars($shop['shop_id']) ?>" <?= $shop['shop_id'] == $order['shop_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($shop['location']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="customerId">Customer</label>
            <select id="customerId" name="customerId" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['customer_id']) ?>" <?= $customer['customer_id'] == $order['customer_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($order['email']) ?>" required>

            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($order['phone']) ?>" required>
            
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($order['address']) ?>" required>

            <label for="orderDetails">Order Details</label>
            <div id="orderDetailsContainer">
                <?php foreach ($orderDetails as $detail): ?>
                    <?php
                    $parts = explode(' x', $detail);
                    $item = trim($parts[0]);
                    $quantity = isset($parts[1]) ? trim($parts[1]) : 1;
                    ?>
                    <div class="order-detail">
                        <select name="orderDetails[]" required>
                            <option value="" disabled>Select Coffee</option>
                            <optgroup label="Hot Coffee">
                                <option value="Hot Espresso: ₱80.00" <?= $item == 'Hot Espresso: ₱80.00' ? 'selected' : '' ?>>Espresso: ₱80.00</option>
                                <option value="Hot Double Espresso: ₱95.00" <?= $item == 'Hot Double Espresso: ₱95.00' ? 'selected' : '' ?>>Double Espresso: ₱95.00</option>
                                <option value="Hot Latte: ₱95.00" <?= $item == 'Hot Latte: ₱95.00' ? 'selected' : '' ?>>Latte: ₱95.00</option>
                                <option value="Hot Cappuccino: ₱95.00" <?= $item == 'Hot Cappuccino: ₱95.00' ? 'selected' : '' ?>>Cappuccino: ₱95.00</option>
                                <option value="Hot Macchiato: ₱100.00" <?= $item == 'Hot Macchiato: ₱100.00' ? 'selected' : '' ?>>Macchiato: ₱100.00</option>
                            </optgroup>
                            <optgroup label="Cold Coffee">
                                <option value="Cold Espresso: ₱90.00" <?= $item == 'Cold Espresso: ₱90.00' ? 'selected' : '' ?>>Espresso: ₱90.00</option>
                                <option value="Cold Double Espresso: ₱105.00" <?= $item == 'Cold Double Espresso: ₱105.00' ? 'selected' : '' ?>>Double Espresso: ₱105.00</option>
                                <option value="Cold Latte: ₱105.00" <?= $item == 'Cold Latte: ₱105.00' ? 'selected' : '' ?>>Latte: ₱105.00</option>
                                <option value="Cold Cappuccino: ₱105.00" <?= $item == 'Cold Cappuccino: ₱105.00' ? 'selected' : '' ?>>Cappuccino: ₱105.00</option>
                                <option value="Cold Macchiato: ₱105.00" <?= $item == 'Cold Macchiato: ₱105.00' ? 'selected' : '' ?>>Macchiato: ₱105.00</option>
                            </optgroup>
                            <optgroup label="Non-Coffee">
                                <option value="Hot Chocolate: ₱80.00" <?= $item == 'Hot Chocolate: ₱80.00' ? 'selected' : '' ?>>Hot Chocolate: ₱80.00</option>
                                <option value="Matcha: ₱95.00" <?= $item == 'Matcha: ₱95.00' ? 'selected' : '' ?>>Matcha: ₱95.00</option>
                                <option value="Milkshake: ₱95.00" <?= $item == 'Milkshake: ₱95.00' ? 'selected' : '' ?>>Milkshake: ₱95.00</option>
                                <option value="Smoothie: ₱95.00" <?= $item == 'Smoothie: ₱95.00' ? 'selected' : '' ?>>Smoothie: ₱95.00</option>
                            </optgroup>
                        </select>
                        <input type="number" name="quantities[]" min="1" value="<?= $quantity ?>" placeholder="Quantity" required>
                        <button type="button" class="remove-button" onclick="removeOrderDetail(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" onclick="addOrderDetail()">Add More</button>
            <button type="submit" name="editOrderBtn">Update Order</button>
        </form>
        <a href="index.php">Back to Orders</a>
    </div>
</body>
</html>