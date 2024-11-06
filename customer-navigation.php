<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/web2/login.php");
    exit;
}

$userName = $_SESSION['user_name'];

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

$conn = mysqli_connect("localhost:3306", "root", "", "web2");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile picture
$sqlGetUser = "SELECT image_path FROM users WHERE name = ?";
$stmt = $conn->prepare($sqlGetUser);
$stmt->bind_param("s", $userName);
$stmt->execute();
$resultUser = $stmt->get_result();

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();
    $profilePic = $user['image_path'];
} else {
    // Default profile picture if none is found
    $profilePic = 'default-profile.png';
}

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $conn->query($sqlGetSettings);

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP SAVVY</title>
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link rel="stylesheet" href="css/customer-navigation.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <style>
        body{
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }
        .profile-pic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-container">
        <a href="customer-dashboard.php?user=<?php echo $userName; ?>">
            <div class="container-header">
                <img class="logo" src="img/<?php echo basename($logoPath); ?>" alt="Logo">
                <label class="shop"><?php echo $shopName; ?></label>
            </div>
        </a>
    </div>
    <div class="menus">
        <a href="customer-dashboard.php">HOME</a>
        <a href="customer-collection.php">COLLECTION</a>
        <a href="#paa">ABOUT US</a>
    </div>
    <div class="info-container">
        <div class="cart-con">
            <a href="cart.php?user=<?php echo $userName; ?>">
                <button class="cart-button">
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </a>
        </div>
        <nav class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="img/<?php echo basename($profilePic); ?>" alt="Profile Picture" class="profile-pic">
                </button>
                <div class="dropdown-content">
                    <a href="user-profile-settings.php">Profile Settings</a>
                    <a href="users-change-password.php">Password</a>
                    <a href="purchases.php">My Purchases</a>
                    <a href="?logout=1">Logout</a>
                </div>
            </div>
        </nav>
    </div>
</div>
</body>
</html>