<?php
require_once 'dbConfig.php';
require_once 'models.php';


if (isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $age = $_POST['age'];

    if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name) && !empty($email) && !empty($address) && !empty($age)) {
        $insertQuery = insertNewUser($pdo, $username, $password, $first_name, $last_name, $email, $address, $age);

        if ($insertQuery) {
            header("Location: ../login.php");
        } else {
            header("Location: ../register.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure all input fields are filled for registration!";
        header("Location: ../register.php");
    }
}

if (isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $loginQuery = loginUser($pdo, $username, $password);

        if ($loginQuery) {
            header("Location: ../index.php");
        } else {
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../login.php");
    }
}

if (isset($_GET['logoutAUser'])) {
    unset($_SESSION['username']);
    unset($_SESSION['user_id']);
    header('Location: ../login.php');
}

if (isset($_POST['placeOrderBtn'])) {
    $firstName    = trim($_POST['firstName']);
    $lastName     = trim($_POST['lastName']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $shopId       = trim($_POST['shopId']);
    $orderDetails = $_POST['orderDetails'];
    $quantities   = $_POST['quantities'];
    $orderDate    = date('Y-m-d H:i:s');

    if (!empty($firstName) && !empty($lastName) && !empty($email) &&
        !empty($phone) && !empty($address) && !empty($shopId) &&
        !empty($orderDetails) && !empty($quantities)) {

        insertCustomer($pdo, $firstName, $lastName, $email, $phone, $address);
        $customerId = $pdo->lastInsertId();

        $orderDetailsFormatted = [];
        foreach ($orderDetails as $index => $detail) {
            $orderDetailsFormatted[] = $detail . ' x' . $quantities[$index];
        }
        $orderDetailsString = implode(', ', $orderDetailsFormatted);

        $query = insertOrder($pdo, $shopId, $customerId, $orderDetailsString, $orderDate, $_SESSION['user_id']);

        if ($query) {
            header("Location: ../index.php");
        } else {
            echo "Order submission failed";
        }
    } else {
        echo "Make sure that no fields are empty";
    }
}

if (isset($_POST['editOrderBtn'])) {
    $orderId      = $_GET['order_id'];
    $shopId       = trim($_POST['shopId']);
    $customerId   = trim($_POST['customerId']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $orderDetails = $_POST['orderDetails'];
    $quantities   = $_POST['quantities'];

    if (!empty($orderId) && !empty($shopId) && !empty($customerId) &&
        !empty($orderDetails) && !empty($quantities)) {

        $orderDetailsFormatted = [];
        foreach ($orderDetails as $index => $detail) {
            $orderDetailsFormatted[] = $detail . ' x' . $quantities[$index];
        }
        $orderDetailsString = implode(', ', $orderDetailsFormatted);

        $query = updateOrder($pdo, $orderId, $shopId, $customerId, $orderDetailsString, $_SESSION['user_id']);

        if ($query) {
            header("Location: ../index.php");
        } else {
            echo "Update failed";
        }
    } else {
        echo "Make sure that no fields are empty";
    }
}


if (isset($_POST['deleteOrderBtn'])) {
    $query = deleteOrder($pdo, $_GET['order_id']);

    if ($query) {
        header("Location: ../index.php");
    } else {
        echo "Deletion failed";
    }
}
?>