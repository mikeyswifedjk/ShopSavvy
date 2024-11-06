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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SELLER DASHBOARD</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/seller-dashboard.css">
</head>
<body>
    <div class="container-content">
        <video autoplay loop muted>
            <source src="img/seller.mp4" type="video/mp4">
            <source src="img/seller.mp4" type="video/mp4">
        </video> 
    </div>
</body>
</html>
