<?php include('customer-navigation.php'); ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

// Check connection
if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    $userName = $_SESSION['user_name'];
} else {
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/web2/login.php");
    exit;
}

// If you want to log out, you can add a condition to check for a logout action
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Clear all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/web2/login.php");
    exit;
}

// Fetch product details based on the product ID from the URL parameter
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM products WHERE product_id = $productId";
$result = mysqli_query($dbConnection, $query);

if (!$result) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

// Get product details
$product = mysqli_fetch_assoc($result);

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $dbConnection->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data ng bawat row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $shopName = $row["shop_name"];
        $logoPath = $row["logo_path"];
    }
} else {
    echo "0 results";
}

// Close the database connection
mysqli_close($dbConnection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUCT DETAILS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="img/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/product-details.css">
</head>
<body>
    
    <div class="content">
        <div class="left">
            <img src="<?php echo isset($product['product_image']) ? htmlspecialchars($product['product_image']) : ''; ?>" alt="<?php echo isset($product['product_name']) ? htmlspecialchars($product['product_name']) : ''; ?>" />
        </div>
        <div class="right">
            <h1><?php echo isset($product['product_name']) ? htmlspecialchars($product['product_name']) : ''; ?></h1>
            <p class="description"><?php echo isset($product['product_description']) ? htmlspecialchars($product['product_description']) : ''; ?></p>
            <p class="peso">₱ <span id="priceRange"><?php echo isset($product['product_price']) ? htmlspecialchars($product['product_price']) : ''; ?></span></p>
            <div class="qty-sales">
                <p class="qty">Stock: <span id="productQty"><?php echo isset($product['product_stocks']) ? htmlspecialchars($product['product_stocks']) : ''; ?></span></p>
                <p class="sales">Sold: <span id="productSales"><?php echo isset($product['product_sales']) ? htmlspecialchars($product['product_sales']) : ''; ?></span></p>
            </div>
            <button onclick="addToCart(<?php echo $productId; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>')">Add to Cart</button>
        </div>
    </div>

     <script>
        function addToCart(productId, productName) {
            var qty = 1;
            window.location.href = 'add-to-cart.php?product_id=' + productId +
                '&product_name=' + encodeURIComponent(productName) +
                '&quantity=' + encodeURIComponent(qty);
        }
    </script>

    <?php include('customer-footer.php'); ?>

</body>
</html>