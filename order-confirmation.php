<?php
session_start();
include('customer-navigation.php');

if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit;
}

$orderDetails = $_SESSION['order_details'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/order-confirmation.css">
</head>
<body>
    <div class="confirmation-container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order!</p>
        <p>Name: <?= htmlspecialchars($orderDetails['name']) ?></p>
        <p>Phone: <?= htmlspecialchars($orderDetails['phone']) ?></p>
        <p>Address: <?= htmlspecialchars($orderDetails['address']) ?></p>
        <p>Total Amount: ₱<?= number_format($orderDetails['total_amount'], 2) ?></p>
        <h2>Order Details</h2>
        <div class="order-items">
            <?php
            $dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");
            $orderIdQuery = "SELECT id FROM orders WHERE user_name = ? ORDER BY id DESC LIMIT 1";
            $orderIdStmt = mysqli_prepare($dbConnection, $orderIdQuery);
            mysqli_stmt_bind_param($orderIdStmt, "s", $_SESSION['user_name']);
            mysqli_stmt_execute($orderIdStmt);
            $orderIdResult = mysqli_stmt_get_result($orderIdStmt);
            $orderRow = mysqli_fetch_assoc($orderIdResult);
            $orderId = $orderRow['id'];

            $orderItemsQuery = "SELECT id, product_name, quantity, price, total_price FROM order_items WHERE order_id = ?";
            $orderItemsStmt = mysqli_prepare($dbConnection, $orderItemsQuery);
            mysqli_stmt_bind_param($orderItemsStmt, "i", $orderId);
            mysqli_stmt_execute($orderItemsStmt);
            $orderItemsResult = mysqli_stmt_get_result($orderItemsStmt);

            while ($row = mysqli_fetch_assoc($orderItemsResult)) {
                echo '<div class="order-item">';
                echo '<p>Product: ' . htmlspecialchars($row['product_name']) . '</p>';
                echo '<p>Quantity: ' . htmlspecialchars($row['quantity']) . '</p>';
                echo '<p>Unit Price: ₱' . htmlspecialchars($row['price']) . '</p>';
                echo '<p>Total Price: ₱' . htmlspecialchars($row['total_price']) . '</p>';
                echo '</div>';
        }

            
            ?>
        </div>
        <p>We will process your order soon. If you have any questions, please contact our customer service.</p>
    </div>
    <?php include('customer-footer.php'); ?>
</body>
</html>
