<?php include('customer-navigation.php'); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$conn) {
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

// Settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1";
$resultSettings = $conn->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data of each row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $shopName = $row["shop_name"];
        $logoPath = $row["logo_path"];
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Purchase History</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/purchases.css">
</head>

<body>
    <h1>PURCHASE HISTORY</h1>
    <?php
    // Prepare SQL query to fetch purchase history
    $selectQuery = "SELECT orders.*, order_items.*, products.product_name AS product_name, products.product_image AS product_image 
                    FROM orders 
                    INNER JOIN order_items ON orders.id = order_items.order_id
                    INNER JOIN products ON order_items.product_name = products.product_name
                    WHERE orders.user_name = ?";
    $selectStatement = mysqli_prepare($conn, $selectQuery);

    if ($selectStatement) {
        mysqli_stmt_bind_param($selectStatement, "s", $userName);
        mysqli_stmt_execute($selectStatement);
        $result = mysqli_stmt_get_result($selectStatement);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Display the purchase history in a table
                echo "<table border='1' cellpadding='10' cellspacing='0'>";
                echo "<tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>";

                while ($item = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $item['product_name'] . "</td>";

                    // Assuming 'product_image' is the alias for the product image column
                    $productImagePath = "" . $item['product_image'];
                    echo "<td><img src='$productImagePath'></td>";

                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>₱" . $item['price'] . "</td>";
                    echo "<td>₱" . $item['total_price'] . "</td>";
                    echo "<td> Approved </td>"; 
                    echo "<td>" . $item['order_date'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "No results found in the purchase history.";
            }
        } else {
            echo "Error fetching results: " . mysqli_error($conn);
        }

        mysqli_stmt_close($selectStatement);
    } else {
        echo "Error in prepared statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    ?>

    <?php include('customer-footer.php'); ?>
</body>

</html>
