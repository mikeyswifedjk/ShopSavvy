<?php
if (isset($_POST["verify_email"])){
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];
    
    // Connect with database
    $conn = mysqli_connect("localhost:3306", "root", "", "web2");
    
    // Mark email as verified
    $sql = "";
    if(isset($_GET['type'])){
        $sql = "UPDATE users SET reset_token = '', reset_token_expiration = '' WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    } else {
        $sql = "UPDATE users SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    }
    
    $result = mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) == 0){
        echo "<script>alert('Verification code failed.'); window.history.back();</script>";
    } else {
        if(isset($_GET['type'])){           
            header("Location:http://localhost/web2/update-password.php?email=".$email);
        } else {
            echo("<script>alert('Successfully Registered!'); document.location.href = 'login.php';</script>");
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
    <title>VERIFY CODE</title>
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/email-verification.css">
</head>

<body>
    <div class="container-content">
        <video autoplay loop muted>
            <source src="img/forgot-password.mp4" type="video/mp4">
            <source src="img/forgot-password.mp4" type="video/mp4">
        </video> 

        <a href="register.php"> <img src="img/logo.png" alt=""> </a>
        <p class="verify-label"> Verify its you.</p>
        <p class="sentences"> We sent verification code to your email account.
            Please check your inbox and enter the 6-digits code.
        </p>

        <form method="POST">
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
            <input type="text" name="verification_code" placeholder="Enter verification code" required /> <br> <br>
            <input type="submit" name="verify_email" value="Verify Email">
        </form>

        <p class="try-again"> Didn't receive in email? <a href=""> Try Again</a></p>
    </div>
</body>
</html>