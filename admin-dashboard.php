<?php include('admin-navigation.php');?>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/admin-dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <h1 class="text1"> SHOPBEE SUMMARY </h1>
    <div class="all-containers">    
    <div class="dashboard-container">
        <?php
            $dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

            if (!$dbConnection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch total sales
            $selectTotalSalesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders";
            $totalSalesResult = mysqli_query($dbConnection, $selectTotalSalesQuery);
            $totalSalesData = mysqli_fetch_assoc($totalSalesResult);
            $totalSales = isset($totalSalesData['total_sales']) ? $totalSalesData['total_sales'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Sales</h2>
            <p>&#8369; <?php echo $totalSales; ?></p>
        </div>

        <?php
            // Fetch total items sold
            $selectTotalItemsSoldQuery = "SELECT SUM(quantity) AS total_items_sold FROM order_items";
                $totalItemsSoldResult = mysqli_query($dbConnection, $selectTotalItemsSoldQuery);
                $totalItemsSoldData = mysqli_fetch_assoc($totalItemsSoldResult);
                $totalItemsSold = isset($totalItemsSoldData['total_items_sold']) ? $totalItemsSoldData['total_items_sold'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Items Sold</h2>
            <p><?php echo $totalItemsSold; ?></p>
        </div>

        <?php
            // Fetch total orders
            $selectTotalOrdersQuery = "SELECT COUNT(id) AS total_orders FROM orders";
            $totalOrdersResult = mysqli_query($dbConnection, $selectTotalOrdersQuery);
            $totalOrdersData = mysqli_fetch_assoc($totalOrdersResult);
            $totalOrders = isset($totalOrdersData['total_orders']) ? $totalOrdersData['total_orders'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Orders</h2>
            <p><?php echo $totalOrders; ?></p>
        </div>

        <?php
            // Fetch total users
            $dbConnectionUsers = mysqli_connect("localhost:3306", "root", "", "web2");

            if (!$dbConnectionUsers) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $selectTotalUsersQuery = "SELECT COUNT(id) AS total_users FROM users";
            $totalUsersResult = mysqli_query($dbConnection, $selectTotalUsersQuery);
            $totalUsersData = mysqli_fetch_assoc($totalUsersResult);
            $totalUsers = isset($totalUsersData['total_users']) ? $totalUsersData['total_users'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Users</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
    </div>
        <?php
            // Fetch best selling items
            $selectBestSellingItemsQuery = "SELECT p.product_name, p.product_image, SUM(o.quantity) AS total_quantity_sold
            FROM order_items o
            JOIN products p ON o.product_name = p.product_name
            GROUP BY o.product_name
            HAVING total_quantity_sold >= 10
            ORDER BY total_quantity_sold";
            $bestSellingItemsResult = mysqli_query($dbConnection, $selectBestSellingItemsQuery);
        ?>
        <div class="container-items">
            <div class="selling-item">
            <h1>BEST SELLING ITEMS</h1>
                <?php
                echo'<div class="rows">';
                while ($row = mysqli_fetch_assoc($bestSellingItemsResult)) {
                    echo '<div class="items">';
                            echo "<img src='{$row['product_image']}' style='max-width: 100px; max-height: 100px;'>";
                            echo "<div>{$row['product_name']}</div>";
                            echo "<div>{$row['total_quantity_sold']} sold</div>";
                            echo '</div>';
                }
                echo '</div>';
                ?>
        </div>

        <?php
            // Fetch slow selling items
            $selectSlowSellingItemsQuery = "SELECT p.product_name, p.product_image, SUM(o.quantity) AS total_quantity_sold
            FROM order_items o
            JOIN products p ON o.product_name = p.product_name
            GROUP BY o.product_name
            HAVING total_quantity_sold <= 10
            ORDER BY total_quantity_sold";
            $slowSellingItemsResult = mysqli_query($dbConnection, $selectSlowSellingItemsQuery);
        ?>
        <div class="selling-item">
            <h1>SLOW SELLING ITEMS</h1>
                <?php
                echo'<div class="rows">';
                while ($row = mysqli_fetch_assoc($slowSellingItemsResult)) {
                    echo '<div class="items">';
                            echo "<img src='{$row['product_image']}' style='max-width: 100px; max-height: 100px;'>";
                            echo "<div>{$row['product_name']}</div>";
                            echo "<div>{$row['total_quantity_sold']} sold</div>";
                            echo '</div>';
                }
                echo'</div>';
                ?>
        </div>
    </div>
    </div>
</body>
</html>