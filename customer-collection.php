<?php include('customer-navigation.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOPSAVVY</title>
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/customer-collection.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        body{
            background-color: white !important;
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }
    </style>
</head>
<body>
    <!-- Top Selling Products Section -->
    <div class="content-allProduct">
        <div class="all-filters">
        <div class="filters-container">
        <div class="search-account">
            <div class="filter"> 
            <label for="sort-products">Sort by Price:</label>
                <select name="sort-products" id="sort-products">
                    <option value="Default">Default</option>
                    <option value="Ascending">Ascending</option>
                    <option value="Descending">Descending</option>
                </select>
                <select name="filter-type" id="filter-type">
                    <option value="">Product Type</option>
                    <option value="Dresses"> Dresses </option>
                    <option value="Tops"> Tops </option>
                    <option value="Bottoms"> Bottoms </option>
                    <option value="Swim Wears"> Swim Wears </option>
                    <option value="Sleep Wears"> Sleep Wears</option>
                    <option value="Jumpsuits"> Sweater </option>
                    <option value="Gowns"> Gowns </option>
                    <option value="Tuxedos"> Tuxedos </option>
                </select>
                <select name="filter-brands" id="filter-brand"></select>
            </div>
        </div>
        </div>
        
        <div class="search-container">
            <input type="search" class="search-item" id="search-input" placeholder="Search..." />
            <i class="fa-solid fa-magnifying-glass" style="color: #AD53A6"></i>
        </div>
        </div>
      

        <div class="product-list">
            <?php
                $conn = mysqli_connect("localhost:3306", "root", "", "web2");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $topSalesQuery = "SELECT * FROM products";
                $topSalesResult = mysqli_query($conn, $topSalesQuery);

                if ($topSalesResult) {
                    while ($product = mysqli_fetch_assoc($topSalesResult)) {
                        echo "<a href='product-details.php?id={$product['product_id']}' class='link'>";
                        echo "<div class='allProduct' data-type='{$product['product_type']}' data-brand='{$product['product_brand']}' data-price='{$product['product_price']}' data-id='{$product['product_id']}'>"; // Add data-id attribute
                        echo "<div class='prod-img'>";
                        echo "<img src='{$product['product_image']}' alt='{$product['product_name']}' />";
                        echo "</div>";
                        echo "<div class='prod-title'>";
                        echo "<span>{$product['product_name']}</span>";
                        echo "</div>";
                        echo "<div class='prod-price'>";
                        echo "<span>₱{$product['product_price']}</span>";
                        echo "</div>";
                        echo "<div class='sold-stock'>";
                        echo "<span>Stock: {$product['product_stocks']}</span>";
                        echo "<span>Sold: {$product['product_sales']}</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }
                    
                } else {
                    echo "Error fetching all products: " . mysqli_error($conn);
                }
            ?>
        </div>
    </div>

    <script src="javascript/dynamic-brands.js"></script>
    <script src="javascript/dynamic-filter-brands.js"></script>
    <script src="javascript/search-filter.js"></script>
    <?php include('customer-footer.php');?>
</body>
</html>