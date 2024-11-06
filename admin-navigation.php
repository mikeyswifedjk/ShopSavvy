<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the newUsername query parameter is set
if (isset($_GET["newUsername"])) {
    // Retrieve the updated username from the query parameter
    $newAdminName = $_GET["newUsername"];
} else {
    // Check if the username is stored in the session
    if (isset($_SESSION['adminUsername'])) {
        // Retrieve the username from the session
        $newAdminName = $_SESSION['adminUsername'];
    } else {
        // Fetch the actual admin username from the database
        $result = mysqli_query($conn, "SELECT username FROM admin WHERE email = 'admin@gmail.com'");
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $newAdminName = $row['username'];
        } else {
            // If fetching from the database fails, use a default value
            $newAdminName = "ADMIN";
        }
    }
}

// Fetch the profile picture path from the database based on the retrieved username
$result = mysqli_query($conn, "SELECT image FROM admin WHERE username = '$newAdminName'");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $profile_picture = $row['image'];
} else {
    // If fetching from the database fails or no image is found, provide a default path
    $profile_picture = "default_image.jpg"; // Update this with your default image path
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Perform logout logic
    session_destroy();
    header("Location: login.php"); // Redirect to the login page after logout
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="bee.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia&effect=neon|outline|emboss|shadow-multiple">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/admin-navigation.css">
    <title>ADMIN PAGE</title>
</head>

<body>
    <div class="sidebar">
        <div class="cnt">
            <a href="admin-account.php?newUsername=<?php echo urlencode($newAdminName); ?>">
                <div class="admin-btn">
                    <img src="<?php echo $profile_picture; ?>" alt="">
                    <label class="username"> <?php echo $newAdminName; ?> </label>
                </div>
            </a>
            <div class="btn">
                <a href="admin-dashboard.php">
                    <i class="fa-solid fa-house-chimney" style="color: #AD53A6;"></i>
                    <span> DASHBOARD </span>
                </a>
                <a href="product_page.php">
                    <i class="fa-solid fa-box-open" style="color: #AD53A6;"></i> 
                    <span> PRODUCT </span>
                </a>
                <a href="product-inventory.php">
                    <i class="fa-solid fa-clipboard-list" style="color: #AD53A6;"></i> 
                    <span> INVENTORY </span>
                </a>
                <a href="orders.php">
                    <i class="fa-solid fa-cart-shopping" style="color: #AD53A6;"></i> 
                    <span> ORDERS </span>
                </a>
                <a href="unlock-user.php"> 
                    <i class="fa-solid fa-user-group" style="color: #AD53A6;"></i> 
                    <span> CUSTOMER </span>
                </a>
                <a href="crud-seller.php"> 
                    <i class="fa-solid fa-users" style="color: #AD53A6;"></i>
                    <span> SELLER </span>
                </a>
                <a href="crud-discounts.php"> 
                    <i class="fa-solid fa-tag" style="color: #AD53A6;"></i>
                    <span> DISCOUNT </span>
                </a>
                <a href="crud-shipping.php"> 
                    <i class="fa-solid fa-truck-fast" style="color: #AD53A6;"></i></i> 
                    <span> SHIPPING </span>
                </a>
                <a href="customer-design-setting.php"> 
                    <i class="fa-solid fa-gears" style="color: #AD53A6;"></i> 
                    <span> UX/UI </span>
                </a>
                <a href="login.php">
                    <i class="fa-solid fa-right-from-bracket" style="color: #AD53A6;"></i> 
                    <span> LOG OUT</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>