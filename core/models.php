<?php
require_once 'dbConfig.php';


function insertNewUser($pdo, $username, $password, $first_name, $last_name, $email, $address, $age) {
    $checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$username]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        $sql = "INSERT INTO user_passwords (username, password, first_name, last_name, email, address, age) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$username, $password, $first_name, $last_name, $email, $address, $age]);

        if ($executeQuery) {
            $_SESSION['message'] = "User successfully inserted";
            return true;
        } else {
            $_SESSION['message'] = "An error occurred from the query";
        }
    } else {
        $_SESSION['message'] = "User already exists";
    }
}

function loginUser($pdo, $username, $password) {
    $sql = "SELECT * FROM user_passwords WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() == 1) {
        $userInfoRow = $stmt->fetch();
        $usernameFromDB = $userInfoRow['username'];
        $passwordFromDB = $userInfoRow['password'];

        if (password_verify($password, $passwordFromDB)) {
            $_SESSION['username'] = $usernameFromDB;
            $_SESSION['user_id'] = $userInfoRow['user_id'];
            $_SESSION['message'] = "Login successful!";
            return true;
        } else {
            $_SESSION['message'] = "Password is invalid, but user exists";
        }
    } else {
        $_SESSION['message'] = "Username doesn't exist in the database. You may consider registration first";
    }
}

function insertCustomer($pdo, $first_name, $last_name, $email, $phone, $address) {
    $sql = "INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$first_name, $last_name, $email, $phone, $address]);
}

function insertOrder($pdo, $shop_id, $customer_id, $order_details, $order_date, $created_by) {
    $sql = "INSERT INTO orders (shop_id, customer_id, order_details, order_date, created_by, updated_by, last_updated) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$shop_id, $customer_id, $order_details, $order_date, $created_by, $created_by]);
}
function getAllOrders($pdo) {
    $sql = "SELECT orders.*, customers.first_name, customers.last_name, customers.email, customers.phone, customers.address, coffee_shops.shop_id, coffee_shops.shop_name, coffee_shops.location, 
                   created_user.username as created_by_username, updated_user.username as updated_by_username,
                   orders.last_updated
            FROM orders 
            JOIN customers ON orders.customer_id = customers.customer_id 
            JOIN coffee_shops ON orders.shop_id = coffee_shops.shop_id
            LEFT JOIN user_passwords as created_user ON orders.created_by = created_user.user_id
            LEFT JOIN user_passwords as updated_user ON orders.updated_by = updated_user.user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllShops($pdo) {
    $sql = "SELECT shop_id, shop_name, location FROM coffee_shops";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getOrderById($pdo, $order_id) {
    $sql = "SELECT orders.*, customers.first_name, customers.last_name, customers.email, customers.phone, customers.address,
                   created_user.username as created_by_username, updated_user.username as updated_by_username
            FROM orders 
            JOIN customers ON orders.customer_id = customers.customer_id
            LEFT JOIN user_passwords as created_user ON orders.created_by = created_user.user_id
            LEFT JOIN user_passwords as updated_user ON orders.updated_by = updated_user.user_id
            WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetch();
}

function updateOrder($pdo, $order_id, $shop_id, $customer_id, $order_details, $updated_by) {
    $sql = "UPDATE orders 
            SET shop_id = ?, customer_id = ?, order_details = ?, updated_by = ?, last_updated = NOW() 
            WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$shop_id, $customer_id, $order_details, $updated_by, $order_id]);
}

function deleteOrder($pdo, $order_id) {
    $sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$order_id]);
}

function getAllCustomers($pdo) {
    $sql = "SELECT customer_id, first_name, last_name FROM customers";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>