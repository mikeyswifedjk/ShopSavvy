<?php
     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\SMTP;
  
     require './PHPMailer/src/PHPMailer.php';
     require './PHPMailer/src/SMTP.php';
     if (isset($_POST["next"])) {
        $email = $_POST["email"];
        // Connect to the database.
        $conn = mysqli_connect("localhost:3306", "root", "", "web2");
        // Check if the email exists in the users table.
        $sql = "SELECT * FROM users WHERE email = '" . $email . "'";
        $result = mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result) == 0) {
            echo "<script>alert('Email not found.'); window.history.back();</script>";
        } else {
            // Generate a unique reset token (you can use random_bytes or any other method).
            $reset_token = bin2hex(random_bytes(16));
            // Calculate the expiration time (e.g., 1 hour from now).
            $expiration_time = date("Y-m-d H:i:s", strtotime("+1 hour"));
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            // Store the reset token and expiration time in the database.
            $update_sql = "UPDATE users SET reset_token = '".$reset_token."', reset_token_expiration = '".$expiration_time."',verification_code = '".$verification_code."' WHERE email = '".$email."'";
            mysqli_query($conn, $update_sql);
         $mail = new PHPMailer(true);
     try {
         //Enable verbose debug output
         $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER; 
         //Send using SMTP
         $mail->isSMTP(); 
         //Set the SMTP server to send through
         $mail->Host = 'smtp.gmail.com'; 
         //Enable SMTP authentication
         $mail->SMTPAuth = true; 
         //SMTP username
         $mail->Username = 'shopbee800@gmail.com';
         //SMTP password
         $mail->Password = 'fqjhitqjtddbxqvz';
         //Enable TLS encryption;
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
         $mail->Port = 587; 
         //Sender
         $mail->setFrom('shopbee800@gmail.com', 'Shopbee');  
         //Add a recipient
         $mail->addAddress($email, $email);
         //Set email format to HTML
         $mail->isHTML(true);
         $mail->Subject = 'Password Reset';
         $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
         //send function to email
         $mail->send();
         } catch (Exception $e) {
             echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
         }
         header("Location:http://localhost/web2/email-verification.php?email=".$email. "&type=password");
     }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORGET PASSWORD</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/forgot-password.css">
</head>

<body>
    <div class="container-content">
        <video autoplay loop muted>
            <source src="img/forgot-password.mp4" type="video/mp4">
            <source src="img/forgot-password.mp4" type="video/mp4">
        </video> 
        
        <p class="forgot-label">Forgot Password?</p>
        <p class="new-password"> New Password</p>
        <p class="email-label">Please enter your email address </p>

        <form method="POST" >
            <input type="email" name="email" placeholder="info@gmail.com" required /><br>
            <input type="submit" name="next" value="Next">
        </form>
        <a href="login.php"><button class="back"> Back</button></a>
    </div>
</body>
</html>