<?php
include('customer-navigation.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

// Check connection
if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_name'])) {
    // Redirect to the login page if not logged in
    header("Location: http://localhost/web2/login.php");
    exit;
}

$userName = $_SESSION['user_name'];

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    session_unset();
    session_destroy();
    header("Location: http://localhost/web2/login.php");
    exit;
}

// Fetch product information from the URL parameters
$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$productName = isset($_GET['product_name']) ? $_GET['product_name'] : '';
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

// Get the user_id based on the user_name
$userQuery = "SELECT id FROM users WHERE name = ?";
$userStatement = mysqli_prepare($dbConnection, $userQuery);
mysqli_stmt_bind_param($userStatement, "s", $userName);
mysqli_stmt_execute($userStatement);
$userResult = mysqli_stmt_get_result($userStatement);

if (!$userResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

$userRow = mysqli_fetch_assoc($userResult);
$user_id = isset($userRow['id']) ? intval($userRow['id']) : 0;

if ($user_id == 0) {
    die("User ID is not detected. Please check the user exists and try again.");
}

// Get product_image and price
$productInfoQuery = "SELECT product_image, product_price FROM products WHERE product_id = ?";
$productInfoStatement = mysqli_prepare($dbConnection, $productInfoQuery);
mysqli_stmt_bind_param($productInfoStatement, "i", $productId);
mysqli_stmt_execute($productInfoStatement);
$productInfoResult = mysqli_stmt_get_result($productInfoStatement);

if (!$productInfoResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

$productInfoRow = mysqli_fetch_assoc($productInfoResult);
$productImage = isset($productInfoRow['product_image']) ? $productInfoRow['product_image'] : '';
$price = isset($productInfoRow['product_price']) ? floatval($productInfoRow['product_price']) : 0.0;

$totalPrice = $quantity * $price;

// Check if the product is already in the cart using prepared statement
$cartQuery = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
$cartStatement = mysqli_prepare($dbConnection, $cartQuery);
mysqli_stmt_bind_param($cartStatement, "ii", $user_id, $productId);
mysqli_stmt_execute($cartStatement);
$cartResult = mysqli_stmt_get_result($cartStatement);

if (!$cartResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

if (mysqli_num_rows($cartResult) == 0) {
    // Start a transaction
    mysqli_begin_transaction($dbConnection);

    // Insert into cart
    $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, quantity, product_image, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertStatement = mysqli_prepare($dbConnection, $insertQuery);
    mysqli_stmt_bind_param($insertStatement, "iisssdd", $user_id, $productId, $productName, $quantity, $productImage, $price, $totalPrice);
    $insertResult = mysqli_stmt_execute($insertStatement);

    if ($insertResult) {
        mysqli_commit($dbConnection);
        echo '<script>';
        echo 'alert("Product added to cart successfully!");';
        echo 'window.history.back();';
        echo '</script>';
    } else {
        mysqli_rollback($dbConnection);
        die("Error in SQL query: " . mysqli_error($dbConnection));
    }
} else {
    // Update the quantity and total price
    $cartRow = mysqli_fetch_assoc($cartResult);
    $newQuantity = $cartRow['quantity'] + $quantity;
    $newTotalPrice = $newQuantity * $price;

    $updateQuery = "UPDATE cart SET quantity = ?, total_price = ? WHERE user_id = ? AND product_id = ?";
    $updateStatement = mysqli_prepare($dbConnection, $updateQuery);
    mysqli_stmt_bind_param($updateStatement, "idii", $newQuantity, $newTotalPrice, $user_id, $productId);
    $updateResult = mysqli_stmt_execute($updateStatement);

    if ($updateResult) {
        echo '<script>';
        echo 'alert("Product quantity updated successfully!");';
        echo 'window.history.back();';
        echo '</script>';
    } else {
        die("Error in SQL query: " . mysqli_error($dbConnection));
    }
}

mysqli_close($dbConnection);
?>
