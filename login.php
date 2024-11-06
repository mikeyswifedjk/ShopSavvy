<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    header("Location: http://localhost/web2/customer-dashboard.php?user=". $_SESSION['user_name']);
}

// Check if a form parameter named "login" has been submitted via the HTTP POST method.
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Define the default admin credentials
    $default_admin_email = "admin@gmail.com";
    $default_admin_password = "admin";
    // Connect to the database
    $conn = mysqli_connect("localhost:3306", "root", "", "web2");

    //Check if there is an existing email address of admin
    $stmt_admin = mysqli_prepare($conn, "SELECT * FROM admin WHERE email = ?");
    mysqli_stmt_bind_param($stmt_admin, "s", $email);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    // Check if credentials are okay, and email is verified
    $stmt_user = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt_user, "s", $email);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);

    // Check if the email exists in the sellers table
    $stmt_seller = mysqli_prepare($conn, "SELECT * FROM seller WHERE email = ?");
    mysqli_stmt_bind_param($stmt_seller, "s", $email);
    mysqli_stmt_execute($stmt_seller);
    $result_seller = mysqli_stmt_get_result($stmt_seller);

    if (mysqli_num_rows($result_admin) > 0) {
        // Admin login
        $user = mysqli_fetch_object($result_admin);
        // Check if the password is incorrect
        if (!password_verify($password, $user->password)) {
            // Increment login attempts for admin
            echo "<script>alert('Password is not correct for admin.'); window.history.back();</script>";
            exit;
        }

        // Set session variables for the logged-in admin
        $_SESSION['user_type'] = 'admin';
        $_SESSION['user_email'] = $email;
        $_SESSION['admin_username'] = $user->username;
        $_SESSION['admin_fullname'] = $user->fullname;

        // You may add further checks or actions for admin login if needed
        header("Location: http://localhost/web2/admin-dashboard.php");
        exit;

    } else if (mysqli_num_rows($result_user) > 0) {
        // Regular user login
        $user = mysqli_fetch_object($result_user);

        // Check if the user is blocked
        if ($user->blocked == 1) {
            echo "<script>alert('Your account is blocked. Contact the admin for assistance.'); window.history.back();</script>";
            exit;
        }

        // Check if the password is incorrect
        if (!password_verify($password, $user->password)) {
            // Increment login attempts for regular user
            recordLoginAttempt($conn, $email);

            // Get the current login attempts
            $loginAttempts = getLoginAttempts($conn, $email);

            // Check if the user has reached the maximum attempts
            $maxAttempts = 3; // Adjust this value as needed

            if ($loginAttempts >= $maxAttempts) {
                // Block the user
                $blockSql = "UPDATE users SET blocked = 1 WHERE email = '$email'";
                mysqli_query($conn, $blockSql);

                echo "<script>alert('Your account is blocked. Contact the admin for assistance.'); window.history.back();</script>";
                exit;
            }

            echo "<script>alert('Password is not correct.'); window.history.back();</script>";
            exit;
        }

        // Check to verify your email
        if ($user->email_verified_at == null && strtolower($email) !== 'admin@gmail.com') {
            echo "<script>alert('Please verify your email <a href=\"email-verification.php?email=" . $email . "\">from here</a>'); window.history.back();</script>";
            exit;
        }

        // Regular user is logged in, redirect to customer landing page
        $_SESSION['user_type'] = 'customer';
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $email;

        // Regular user is logged in, redirect to customer landing page
        header("Location: http://localhost/web2/customer-dashboard.php");
        exit;
    } else if (mysqli_num_rows($result_seller) > 0) {
        // Seller login
        $seller = mysqli_fetch_object($result_seller);

        // Check if the seller is blocked
        if ($seller->blocked == 1) {
            echo "<script>alert('Your account is blocked. Contact the admin for assistance.'); window.history.back();</script>";
            exit;
        }

        // Check if the password is incorrect
        if (!password_verify($password, $seller->password)) {
            echo "<script>alert('Password is not correct.'); window.history.back();</script>";
            exit;
        }

        // Seller is logged in, redirect to seller dashboard
        $_SESSION['user_type'] = 'seller';
        $_SESSION['user_name'] = $seller->username;
        $_SESSION['user_email'] = $email;

        header("Location: http://localhost/web2/seller-dashboard.php");
        exit;
    } else {
        // Email not found
        echo "<script>alert('Email not found.'); window.history.back();</script>";
        exit;
    }
}

function recordLoginAttempt($conn, $email) {
    // Check if there is a record for the user in the login_attempts table
    $sql = "SELECT * FROM users WHERE email = '" . $email . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<script>alert('SQL error: " . mysqli_error($conn) . "');</script>";  // Debugging output for query errors
    }
    if (mysqli_num_rows($result) == 0) {
        // Insert a new record if it doesn't exist
        $insertSql = "INSERT INTO users (email, attempts) VALUES ('$email', 1)";
        mysqli_query($conn, $insertSql);
    } else {
        // Update the existing record
        $updateSql = "UPDATE users SET attempts = attempts + 1 WHERE email = '$email'";
        mysqli_query($conn, $updateSql);
    }
}

function getLoginAttempts($conn, $email) {
    $sql = "SELECT attempts FROM users WHERE email = '" . $email . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<script>alert('SQL error: " . mysqli_error($conn) . "');</script>"; // Debugging output for query errors
    }

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['attempts'];
    } else {
        return 0; // Return 0 if there are no login attempts
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title>LOGIN</title>
</head>

<body>
    <div class="container-content">   

        <div class="content">
            <h3> ShopSavvy </h3>
            <h6> Login to access your account. </h6>
            <form method="POST">
                <label class="email"> Email </label>
                <input type="email" name="email" placeholder="Enter your email address..." required /><br>
                <label class="password"> Password </label>
                <input type="password" name="password" placeholder="Enter your password..." required /><br>
                <p class="forget"><a href="forgot-password.php">Forgot Password?</a></p>
                <input type="submit" name="login" value="Login">
                <p class="signup">New to ShopSavvy? <a href="register.php"> Register </a></p>
            </form>
        </div> 

        <img src="img/login-signup.png" alt="">

    </div>
</body>
</html>
