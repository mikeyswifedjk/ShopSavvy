<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Function to check if an email already exists in the database
function isEmailUnique($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    return (mysqli_num_rows($result) == 0);
}

// Check if a form parameter named "register" has been submitted via the HTTP POST method.
if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["confirm_password"];
    $phone_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];
    $image = $_POST['image_path'];

    // Check if the email is unique (not already in the database)
    $conn = mysqli_connect("localhost:3306", "root", "", "web2");

    if (!isEmailUnique($conn, $email)) {
        echo "<script>alert('Email already exists. Please choose a different email.'); window.history.back();</script>";
    } else {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Enable verbose debug output
            $mail->SMTPDebug = 0; // SMTP::DEBUG_SERVER;
            // Send using SMTP
            $mail->isSMTP();
            // Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';
            // Enable SMTP authentication
            $mail->SMTPAuth = true;
            // SMTP username
            $mail->Username = 'shopbee800@gmail.com'; // email that will be host
            // SMTP password
            $mail->Password = 'fqjhitqjtddbxqvz'; // app name password
            // Enable TLS encryption;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->Port = 587;
            // Sender
            $mail->setFrom('shopbee800@gmail.com', 'Shopbee');
            // Add a recipient
            $mail->addAddress($email, $name);
            // Set email format to HTML
            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            // send function to email
            $mail->send();

            // Insert the user into the database
            $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
            // insert in users table
            $sql = "INSERT INTO users (name, email, password, verification_code, email_verified_at, attempts, contact_number, address, first_name, middle_name, last_name, image_path) VALUES ('$name', '$email', '$encrypted_password', '$verification_code', NULL, 0, '$phone_number', '$address', '$fname', '$mname', '$lname', '$image')";

            // Execute the SQL query only if it's not an admin account
            mysqli_query($conn, $sql);

            // Redirect to the email verification page
            header("Location:http://localhost/web2/email-verification.php?email=" . $email);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: " . $e->getMessage();
        }
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
    <link rel="stylesheet" type="text/css" href="css/register.css">
    <title>REGISTER</title>
</head>

<body>
    <div class="container-content">  
    
        <img src="img/login-signup.png" alt="">
        
        <div class="content">
            <h3>ShopSavvy </h3> 
            <h6> Sign up now to become a member.</h6>   
            <form method="POST" onsubmit="return validateForm();">
                <input type="text" id="first_name" name="first_name" placeholder="First Name" required /><br>
                <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" required /><br>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" required /><br>
                <input type="text" id="address" name="address" placeholder="Address" required /><br> 
                <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number" required /><br>
                <input type="text" id="username" name="name" placeholder="Username" required /><br>
                <input type="email" id="email" name="email" placeholder="info@gmail.com" required /><br>
                <input type="password" name="password" id="password" placeholder="Password" required /><br>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required /><br>            
                <input type="submit" name="register" value="REGISTER">
                <input type="checkbox" name="word" id="word" required />
                <label for="word">By signing up, You agree to ShopBee's 
                    Terms of Service & Privacy Policy.
                </label>
                <p class="login">Have an account? <a href="login.php"> Login </a></p>
            </form>

        </div>

    </div>

    <script>
         function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            // Check if password and confirm password are equal
            if (password !== confirmPassword) {
                alert('Password and Confirm Password do not match.');
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</body>
</html>