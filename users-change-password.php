<?php include 'change-password.php'; ?>

<?php include('customer-navigation.php');?>

<?php
    // Database connection
    $dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

    // Check connection
    if (!$dbConnection) {
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

        // Retrieve user information from the database
        $query = "SELECT image_path, address, first_name, middle_name, last_name, contact_number FROM users WHERE name='$userName'";
        $result = mysqli_query($dbConnection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Save the user information in session variables
            $_SESSION['image_path'] = $row['image_path'];
            $_SESSION['address'] = $row['address'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['middle_name'] = $row['middle_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['contact_number'] = $row['contact_number'];    
            
    }

    //settings for customer-design-settings
    $sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
    $resultSettings = $dbConnection->query($sqlGetSettings);

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
        <title>SHOPSAVVY</title>
        <link rel="icon" type="image/png" href="img/logo.png" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia&effect=neon|outline|emboss|shadow-multiple">
        <link rel="stylesheet" type="text/css" href="css/user-change-password.css">
    </head>

    <body>
    <div class="settings">
        <h1>PASSWORD SETTINGS</h1>
        <p> Manage password and security</p> <br> <br>
        <!-- Form for changing the password -->
        <form method="post">
            <!-- Current Password -->
            <label for="current_password">Current Password:</label> <br>
            <input type="password" id="current_password" name="current_password" placeholder="Enter your old password" required> <br> <br>

            <label for="password">New Password:</label> <br>
            <input type="password" id="password" name="password" placeholder="Enter your new password" value="" required /> <br> <br>

            <!-- Confirm New Password -->
            <label for="confirm_password">Confirm New Password:</label> <br>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your new password" value="" required /> <br> <br>

            <!-- Submit Button -->
            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>

    <?php include('customer-footer.php');?>
</body>
</html>