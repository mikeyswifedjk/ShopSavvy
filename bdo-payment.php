<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit;
}

$orderDetails = $_SESSION['order_details'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bdoAccountNumber = $_POST['bdo_account_number'];
    $bdoAmount = $_POST['bdo_amount'];

    if ($bdoAmount == $orderDetails['total_amount']) {
        // Proceed with payment and redirection
        // Your payment processing code here...

        // Redirect to order confirmation
        header("Location: order-confirmation.php");
        exit;
    } else {
        // Store error message in session
        $_SESSION['payment_error'] = "The payment amount must be exactly ₱" . number_format($orderDetails['total_amount'], 2);

        // Redirect back to payment page
        header("Location: bdo-payment.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDO Payment</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/bdo-payment.css">
</head>
<body>
    <div class="background-image">
        <img src="img/bg-bdo.png" alt="bdo" />
    </div>
    <div class="payment-container">
        <h1>BD<span>O</span> Payment</h1>
        <p>Please complete the payment using BDO.</p>
        <p>Total Amount: ₱<?= number_format($orderDetails['total_amount'], 2) ?></p>
        <?php if (isset($_SESSION['payment_error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['payment_error']) ?></p>
            <?php unset($_SESSION['payment_error']); // Clear the error message ?>
        <?php endif; ?>
        <form action="bdo-payment.php" method="POST">
            <label for="bdo_account_number">BDO Account Number:</label>
            <input type="text" name="bdo_account_number" id="bdo_account_number" placeholder="xxx-xxx-xxx">
            <label for="bdo_amount">Amount:</label>
            <input type="number" step="0.01" name="bdo_amount" id="bdo_amount" required>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>