<?php include('admin-navigation.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/product-inventory.css">
    <title>PRODUCT INVENTORY</title>
</head>

<body>
    <h1 class="text1">PRODUCT INVENTORY</h1>
    <button class="report-btn" onclick="generateReport()">Generate Report</button>

    <script>
    function generateReport() {
        // Redirect to the report.php page
        window.location.href = 'inventory-report.php';
    }
    </script>

    <!-- Add this form inside the body tag, before the table -->
    <div class="all">
    <form method="GET" action="">
        <input class="search-bar" type="text" id="searchProductName" name="searchProductName" placeholder="Search product name..." required />
        <button class="search-button" type="submit">
            <i class="fa-solid fa-magnifying-glass" style="color: #502779;"></i>
        </button>
    </form>

        <?php
        // Establish a database connection
        $dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

        if (!$dbConnection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch products and their variants from the database
        // Define the initial query without the search condition
        $selectProductsQuery = "SELECT * FROM products";

        // Check if a search query is provided
        if (isset($_GET['searchProductName'])) {
            $searchProductName = $_GET['searchProductName'];

            // Validate input to prevent SQL injection
            $searchProductName = mysqli_real_escape_string($dbConnection, $searchProductName);

            // Modify the initial query to include the search condition
            $selectProductsQuery = "SELECT * FROM products WHERE product_name LIKE '%$searchProductName%'";
        }

        // Execute the modified query
        $productsResult = mysqli_query($dbConnection, $selectProductsQuery);

        if (!$productsResult) {
            die("Error in SQL query: " . mysqli_error($dbConnection));
        }

        echo "<table border='1' cellpadding='20' cellspacing='0'>";
        echo "<tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Product Type</th>
        <th>Product Brand</th>
        <th>Product Price</th>
        <th>Product Quantity</th>
        <th>Product Sales</th>
        <th>Stocks Available</th>
        </tr>";

        while ($product = mysqli_fetch_assoc($productsResult)) {
            $stockAvailability = $product['product_stocks'] - $product['product_sales'];

            echo "<tr>";
            echo "<td>" . $product['product_id'] . "</td>";
            echo "<td>" . $product['product_name'] . "</td>";
            echo "<td>" . $product['product_type'] . "</td>";
            echo "<td>" . $product['product_brand'] . "</td>";
            echo "<td>" . $product['product_price'] . "</td>";
            echo "<td>" . $product['product_stocks'] . "</td>";
            echo "<td>" . $product['product_sales'] . "</td>";
            echo "<td>" . $stockAvailability . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        mysqli_close($dbConnection); // Close the database connection
        ?>
    </div>
</body>

</html>