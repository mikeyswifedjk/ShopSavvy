<?php include('customer-navigation.php');?>

<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    $userName = $_SESSION['user_name'];
} else {
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/web2/login.php");
    exit;
}

// // If you want to log out, you can add a condition to check for a logout action
// if (isset($_GET['logout']) && $_GET['logout'] == 1) {
//     // Clear all session variables
//     session_unset();
//     // Destroy the session
//     session_destroy();
//     // Redirect to the login page or handle accordingly
//     header("Location: http://localhost/web2/login.php");
//     exit;
// }

$conn = mysqli_connect("localhost:3306", "root", "", "web2");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $conn->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data ng bawat row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $imageOnePath = $row["image_one_path"];
        $imageTwoPath = $row["image_two_path"];
        $imageThreePath = $row["image_three_path"];
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/customer-dashboard.css">
    <title>SHOPSAVVY</title>
    <style>
        body{
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }
    </style>
</head>
<body>
    <!-- Image Slider -->
    <div class="container-imageSlider">
            <div class="mySlides fade">
                <img class="imageOne" src="img/<?php echo basename($imageOnePath); ?>" alt="Image One">
            </div>

            <div class="mySlides fade">
                <img class="imageTwo" src="img/<?php echo basename($imageTwoPath); ?>" alt="Image Two">
            </div>

            <div class="mySlides fade">
                <img class="imageThree" src="img/<?php echo basename($imageThreePath); ?>" alt="Image Three">
            </div>
        </div>

        <div class="DOT" style="text-align: center">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        <!-- Top Selling Products Section -->
        <div class="content-topProduct">
            <div id="top-products-txt">
                <p>TOP PRODUCTS</p>
            </div>

            <div class="containers-topProduct">
                <?php
                // Fetch top sales products
                $topSalesQuery = "SELECT * FROM products ORDER BY product_sales DESC LIMIT 8";

                $topSalesResult = mysqli_query($conn, $topSalesQuery);

                if ($topSalesResult) {
                    while ($product = mysqli_fetch_assoc($topSalesResult)) {
                        echo "<a href='product-details.php?id={$product['product_id']}' class='link'>";
                        echo "<div class='topProduct'>";
                        echo "<div class='prod-img'>";
                        echo "<img src='{$product['product_image']}' alt='{$product['product_name']}' />";
                        echo "</div>";
                        echo "<div class='prod-title'>";
                        echo "<span>{$product['product_name']}</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }
                } else {
                    echo "Error fetching top sales products: " . mysqli_error($conn);
                }
                ?>
            </div>
        </div>

        <script>
            // Image Slider
            let slideIndex = 0;
            showSlides();

            function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slideIndex++;

            if (slideIndex > slides.length) {
                slideIndex = 1; // Reset slideIndex to 1 for continuous loop
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 2000); // Change slide every 2000 milliseconds (2 seconds)
            }

        </script>

        <?php include('customer-footer.php');?>
</body>
</html>