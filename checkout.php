<?php
session_start();
include('customer-navigation.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_name'])) {
    header("Location: http://localhost/web2/login.php");
    exit;
}

$userName = $_SESSION['user_name'];
$selectedItems = isset($_SESSION['selected_items']) ? explode("-", $_SESSION['selected_items']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $regionId = $_POST['region'];
    $discountCode = $_POST['discount_code'];
    $paymentMethod = $_POST['payment_method'];

    $shippingQuery = "SELECT fee FROM shipping WHERE id = ?";
    $shippingStmt = mysqli_prepare($dbConnection, $shippingQuery);
    mysqli_stmt_bind_param($shippingStmt, "i", $regionId);
    mysqli_stmt_execute($shippingStmt);
    $shippingResult = mysqli_stmt_get_result($shippingStmt);
    $shippingRow = mysqli_fetch_assoc($shippingResult);
    $shippingFee = isset($shippingRow['fee']) ? $shippingRow['fee'] : 0;

    $discountQuery = "SELECT amount FROM discounts WHERE code = ? AND status = 'active' LIMIT 1";
    $discountStmt = mysqli_prepare($dbConnection, $discountQuery);
    mysqli_stmt_bind_param($discountStmt, "s", $discountCode);
    mysqli_stmt_execute($discountStmt);
    $discountResult = mysqli_stmt_get_result($discountStmt);
    $discountRow = mysqli_fetch_assoc($discountResult);
    $discountAmount = isset($discountRow['amount']) ? $discountRow['amount'] : 0;

    $totalAmount = 0;
    foreach ($selectedItems as $itemId) {
        $cartItemQuery = "SELECT product_name, quantity, price, total_price FROM cart WHERE id = ?";
        $cartItemStmt = mysqli_prepare($dbConnection, $cartItemQuery);
        mysqli_stmt_bind_param($cartItemStmt, "i", $itemId);
        mysqli_stmt_execute($cartItemStmt);
        $cartItemResult = mysqli_stmt_get_result($cartItemStmt);
        $cartItemRow = mysqli_fetch_assoc($cartItemResult);
        $totalAmount += $cartItemRow['total_price'];
    }
    $totalAmount += $shippingFee;
    $totalAmount -= $discountAmount;

    // Store necessary information in session
    $_SESSION['order_details'] = [
        'selected_items' => $selectedItems,
        'name' => $name,
        'phone' => $phone,
        'address' => $address,
        'region_id' => $regionId,
        'discount_code' => $discountCode,
        'payment_method' => $paymentMethod,
        'total_amount' => $totalAmount,
        'product_images' => [] // Initialize an array to store product images
    ];

    foreach ($selectedItems as $itemId) {
        $cartItemImageQuery = "SELECT product_image FROM cart WHERE id = ?";
        $cartItemImageStmt = mysqli_prepare($dbConnection, $cartItemImageQuery);
        mysqli_stmt_bind_param($cartItemImageStmt, "i", $itemId);
        mysqli_stmt_execute($cartItemImageStmt);
        $cartItemImageResult = mysqli_stmt_get_result($cartItemImageStmt);
        $cartItemImageRow = mysqli_fetch_assoc($cartItemImageResult);
        $_SESSION['order_details']['product_images'][$itemId] = $cartItemImageRow['product_image'];
    }

    // Insert order details into the orders table
    $orderQuery = "INSERT INTO orders (user_name, name, phone, address, region_id, discount_code, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $orderStmt = mysqli_prepare($dbConnection, $orderQuery);
    mysqli_stmt_bind_param($orderStmt, "ssssissd", $userName, $name, $phone, $address, $regionId, $discountCode, $paymentMethod, $totalAmount);
    mysqli_stmt_execute($orderStmt);

    // Retrieve the ID of the inserted order
    $orderId = mysqli_insert_id($dbConnection);

    // Insert each selected item into the order_items table
    foreach ($selectedItems as $itemId) {
        $cartItemQuery = "SELECT product_name, quantity, price, total_price FROM cart WHERE id = ?";
        $cartItemStmt = mysqli_prepare($dbConnection, $cartItemQuery);
        mysqli_stmt_bind_param($cartItemStmt, "i", $itemId);
        mysqli_stmt_execute($cartItemStmt);
        $cartItemResult = mysqli_stmt_get_result($cartItemStmt);
        $cartItemRow = mysqli_fetch_assoc($cartItemResult);

        $insertItemQuery = "INSERT INTO order_items (order_id, product_name, product_image, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?)";
        $insertItemStmt = mysqli_prepare($dbConnection, $insertItemQuery);
        mysqli_stmt_bind_param($insertItemStmt, "isssdd", $orderId, $cartItemRow['product_name'], $_SESSION['order_details']['product_images'][$itemId], $cartItemRow['quantity'], $cartItemRow['price'], $cartItemRow['total_price']);
        mysqli_stmt_execute($insertItemStmt);

    }

    // Clear selected items from cart
    $clearCartQuery = "DELETE FROM cart WHERE user_id = (SELECT id FROM users WHERE name = ?) AND id IN (" . implode(',', array_map('intval', $selectedItems)) . ")";
    $clearCartStmt = mysqli_prepare($dbConnection, $clearCartQuery);
    mysqli_stmt_bind_param($clearCartStmt, "s", $userName);
    mysqli_stmt_execute($clearCartStmt);

    // Redirect based on payment method
    if ($paymentMethod === "GCash") {
        header("Location: gcash-payment.php");
    } elseif ($paymentMethod === "BDO") {
        header("Location: bdo-payment.php");
    } else {
        header("Location: order-confirmation.php");
    }
    exit;
}

// Fetch shipping regions
$regionQuery = "SELECT id, address, fee FROM shipping";
$regionResult = mysqli_query($dbConnection, $regionQuery);
$regions = [];
while ($row = mysqli_fetch_assoc($regionResult)) {
    $regions[] = $row;
}

// Fetch active discount codes
$discountQuery = "SELECT code, amount FROM discounts WHERE status = 'active'";
$discountResult = mysqli_query($dbConnection, $discountQuery);
$discounts = [];
while ($row = mysqli_fetch_assoc($discountResult)) {
    $discounts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECKOUT</title>
    <link rel="stylesheet" type="text/css" href="css/checkout.css">
</head>
<body>
    <div class="checkout-container">
    <form id="checkout-form" method="POST" action="checkout.php">
        <div class="products">
            <h2>PRODUCT LIST</h2>
            <div class="cart-summary">
                <?php
                $totalQuantity = 0;
                $totalPrice = 0;
                foreach ($selectedItems as $itemId) {
                    $cartItemQuery = "SELECT product_name, quantity, price, total_price, product_image FROM cart WHERE id = ?";
                    $cartItemStmt = mysqli_prepare($dbConnection, $cartItemQuery);
                    mysqli_stmt_bind_param($cartItemStmt, "i", $itemId);
                    mysqli_stmt_execute($cartItemStmt);
                    $cartItemResult = mysqli_stmt_get_result($cartItemStmt);
                    $row = mysqli_fetch_assoc($cartItemResult);

                    $totalQuantity += $row['quantity'];
                    $totalPrice += $row['total_price'];

                    echo '<div class="cart-item">';
                    echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '" width="50" height="50">';
                    echo '<div class="cart-item-details">';
                    echo '<p class="product-name">Product: ' . $row['product_name'] . '</p>';
                    echo '<p class="qty">Quantity: ' . $row['quantity'] . '</p>';
                    echo '<p class="price">Unit Price: ₱' . $row['price'] . '</p>';
                    echo '<p class="total-price">Total Price: ₱' . $row['total_price'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
</div>
        <div class="shipping-details">
            <h2>SHIPPING DETAILS</h2>
            <div class="shipment-details">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="phone">Contact Number:</label>
                <input type="text" name="phone" id="phone" required>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
                <label for="region">Region:</label>
                <select name="region" id="region" required>
                    <option value="">Select a region</option>
                    <?php foreach ($regions as $region) : ?>
                        <option value="<?= $region['id'] ?>"><?= $region['address'] ?> (₱<?= $region['fee'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="discount-promo">
            <h2>PROMO &amp; DISCOUNT </h2>
            <div class="discount-section">
                <select name="discount_code" id="discount_code">
                    <option value="">None</option>
                    <?php foreach ($discounts as $discount) : ?>
                        <option value="<?= $discount['code'] ?>"><?= $discount['code'] ?> (₱<?= $discount['amount'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="payment-method">
            <h2>PAYMENT METHOD</h2>
            <div class="payment-options">
                <label>
                    <input type="radio" name="payment_method" value="COD" required> 
                    <img src="img/cod.png" alt="COD">
                </label>
                <label>
                    <input type="radio" name="payment_method" value="BDO">
                    <img src="img/bdo.png" alt="BDO">
                </label>
                <label>
                    <input type="radio" name="payment_method" value="GCash">
                    <img src="img/gcash.png" alt="GCash">
                </label>
            </div>
        </div>
        <div class="order-summary">
            <h2>ORDER SUMMARY</h2>
            <div class="order-summary-details">
                <p>Sub total: ₱<span id="subtotal"><?= $totalPrice ?></span></p>
                <p>Shipping Fee: ₱<span id="shipping_fee">0</span></p>
                <p>Discount: ₱<span id="discount_amount">0</span></p>
                <p style="color: red;">Total Amount: ₱<span id="total_amount"><?= $totalPrice ?></span></p>
            </div>
        </div>
        <button type="submit">PLACE ORDER</button>
        </form>
    </div>
    <?php include('customer-footer.php'); ?>

    <script>
        document.getElementById('region').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var shippingFee = parseFloat(selectedOption.text.match(/\(₱(\d+(\.\d{1,2})?)\)/)[1]);
            document.getElementById('shipping_fee').textContent = shippingFee.toFixed(2);
            updateTotalAmount();
        });

        document.getElementById('discount_code').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var discountAmount = selectedOption.value ? parseFloat(selectedOption.text.match(/\(₱(\d+(\.\d{1,2})?)\)/)[1]) : 0;
            document.getElementById('discount_amount').textContent = discountAmount.toFixed(2);
            updateTotalAmount();
        });

        function updateTotalAmount() {
            var subtotal = parseFloat(document.getElementById('subtotal').textContent);
            var shippingFee = parseFloat(document.getElementById('shipping_fee').textContent);
            var discountAmount = parseFloat(document.getElementById('discount_amount').textContent);
            var totalAmount = subtotal + shippingFee - discountAmount;
            document.getElementById('total_amount').textContent = totalAmount.toFixed(2);
        }
    </script>
</body>
</html>
