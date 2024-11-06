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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/customer-footer.css">
    <title>SHOP SAVVY</title>
    <style>
        body{
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }
    </style>
</head>
<body>
    <!-- Footer -->
    <footer class="footer" id="paa">
        <div class="footer-container">
            <!--Row Container-->
            <div class="row-container">
            <!--About-->
            <div class="footer-about">
                <h3>About Us</h3>
                <p>
                    We're buzzing with excitement to bring you a world of e-commerce wonders. 
                    With a commitment to convenience, quality, and customer satisfaction, 
                    we aim to be your go-to destination for all your online shopping needs.
                </p>
                <div class="footer-social">
                <a href=""><i class="fab fa-twitter"></i></a>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-youtube"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
                <a href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!--Contact-->
            <div class="footer-contact">
                <h3>Get In Touch</h3>
                <p><i class="fa fa-phone-alt"></i>+012 345 67890</p>
                <p><i class="fa fa-envelope"></i>shopbee800@gmail.com</p>
                <p><i class="fa-solid fa-warehouse"></i> Bustos, Bulacan Philippines</p>
            </div>

            <!--Project-->
            <div class="footer-project">
                <h3>ShopSavvy Logo</h3>
                <a href=""><img src="img/logo.png" alt="" /></a>
                <a href=""><img src="img/login-signup.png" alt="" /></a>
            </div>
            </div>
        </div>

        <!--Copyright-->
        <div class="copyright">
            <div class="copyright-container">
            <div class="row-items">
                <div class="copy-text">
                <p>&copy; <a href="file:///Users/maikaordonez/Documents/HTML/FINAL%20PROJECT%20(3A)/index.html">
                2023 ShopSavvy</a>. All Rights Reserved</p>
                </div>
                <div class="copy-menu">
                <a href="">Terms & Conditions</a>
                <a href="">Privacy Policy</a>
                <a href="https://www.facebook.com/maika.ordonez.3">Designer</a>
                </div>
            </div>
            </div>
        </div>
        </footer>
</body>
</html>