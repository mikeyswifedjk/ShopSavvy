<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit;
}

$orderDetails = $_SESSION['order_details'];
$error = ''; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gcashNumber = $_POST['gcash_number'];
    $gcashAmount = $_POST['gcash_amount'];

    if ($gcashAmount == $orderDetails['total_amount']) {
        // Proceed with payment and redirection
        // Your payment processing code here...

        // Redirect to order confirmation
        header("Location: order-confirmation.php");
        exit;
    } else {
        $error = "The payment amount must be exactly ₱" . number_format($orderDetails['total_amount'], 2);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/gcash-payment.css">
</head>
<body>
    <div class="background-image">
        <img src="img/bg-gcash.jpeg" alt="gcash" />
    </div>
    <div class="payment-container">
        <h1>GCash Payment</h1>
        <p>Please complete the payment using GCash.</p>
        <p>Total Amount: ₱<?= number_format($orderDetails['total_amount'], 2) ?></p>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="gcash-payment.php" method="POST">
            <label for="gcash_number">GCash Number:</label>
            <input type="text" name="gcash_number" id="gcash_number" placeholder="09xx-xxx-xxxx" required>
            <label for="gcash_amount">Amount:</label>
            <input type="number" step="0.01" name="gcash_amount" id="gcash_amount" required>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
