<?php include('admin-navigation.php');?>

<?php
// Default values if no data is retrieved from the database
$existingFullname = "";
$existingUsername = "";
$existingAddress = "";
$existingPhonenumber = "";
$existingImage = "";  // Initialize the variable

// Fetch existing admin information from the database
$conn = mysqli_connect("localhost:3306", "root", "", "web2");

if ($conn) {
    $selectInfoSql = "SELECT * FROM admin WHERE email = 'admin@gmail.com'";
    $result = mysqli_query($conn, $selectInfoSql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Assign existing values to variables
        if ($row) {
            $existingFullname = $row['fullname'];
            $existingUsername = $row['username'];
            $existingAddress = $row['address'];
            $existingPhonenumber = $row['phone_number'];
            $existingImage = $row['image'];
        }

        mysqli_free_result($result);
    } else {
        echo "<script>alert('Error fetching admin information: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/admin-account.css">
    <title>ACCOUNT SETTINGS</title>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Check if a form parameter named "changePassword" has been submitted via the HTTP POST method.
    if (isset($_POST["changePassword"])) {
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        // Check if the new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            echo "<script>alert('New password and confirm password do not match.');</script>";
        } else {
            // Hash the new password before updating
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $conn = mysqli_connect("localhost:3306", "root", "", "web2");

            // Update the default admin password in the database
            $updateSql = "UPDATE admin SET password = '$hashedPassword' WHERE email = 'admin@gmail.com'";
            mysqli_query($conn, $updateSql);

            echo "<script>alert('Default admin password changed successfully.');</script>";
        }
    }

    if (isset($_POST["changeInfo"])) {
        $full_name = $_POST["newFullname"];
        $username = $_POST["newUsername"];
        $address = $_POST["newAddress"];
        $phone_number = $_POST["newPhonenumber"];

        if (isset($_FILES["newImg"]) && $_FILES["newImg"]["error"] == 0) {
            $uploadDir = "img/"; // Specify your upload directory
        
            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
        
            $uploadFile = $uploadDir . basename($_FILES["newImg"]["name"]);
        
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["newImg"]["tmp_name"], $uploadFile)) {
                $profile_picture = $uploadFile;
            } else {
                echo "<script>alert('Failed to upload profile picture. Error: " . $_FILES["newImg"]["error"] . "');</script>";
            }
        }

        // Update the admin information in the database
        $conn = mysqli_connect("localhost:3306", "root", "", "web2");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Use prepared statement to prevent SQL injection
        $updateInfoSql = "UPDATE admin SET fullname = ?, username = ?, address = ?, phone_number = ?, image = ? WHERE email = 'admin@gmail.com'";

        $stmt = mysqli_prepare($conn, $updateInfoSql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $full_name, $username, $address, $phone_number, $profile_picture);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Admin information updated successfully.'); document.location.href = 'admin-account.php';</script>";
            } else {
                echo "<script>alert('Error updating admin information: " . mysqli_error($conn) . "');</script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "');</script>";
        }

        mysqli_close($conn);
    }
    ?>
    
    <div class="tab-container">
        <div class="tab active-tab" onclick="showTab('account')">Account Settings</div>
        <div class="tab" onclick="showTab('password')">Password Settings</div>
    </div>

    <div id="account-tab">
        <div class="account-form">
            <h1>ACCOUNT SETTINGS</h1>
            <p>Manage and protect your account</p> <br> <br>

            <form method="POST" enctype="multipart/form-data">
                <label class="tflabel"> FULL NAME</label> <br>
                <input type="text" id="newFullname" name="newFullname" placeholder="Full Name" value="<?= isset($existingFullname) ? htmlspecialchars($existingFullname) : ''; ?>" required /><br><br>
                
                <label class="tflabel"> USERNAME</label> <br>
                <input type="text" id="newUsername" name="newUsername" placeholder="Username" value="<?= isset($existingUsername) ? htmlspecialchars($existingUsername) : ''; ?>" required /><br><br>
                
                <label class="tflabel"> ADDRESS</label> <br>
                <input type="text" id="newAddress" name="newAddress" placeholder="Address" value="<?= isset($existingAddress) ? htmlspecialchars($existingAddress) : ''; ?>" required /><br><br>
                
                <label class="tflabel"> PHONE NUMBER</label> <br>
                <input type="text" id="newPhonenumber" name="newPhonenumber" placeholder="Phone Number" value="<?= isset($existingPhonenumber) ? htmlspecialchars($existingPhonenumber) : ''; ?>" required /><br><br>

                <div class="imageProd">
                    <img src="<?= htmlspecialchars($existingImage); ?>" id="imagePreview" alt="Image Preview">
                    <span class="profile"> PROFILE PICTURE </span>
                </div>

                <input type="file" id="newImg" name="newImg" onchange="previewImage(this);"><br><br>
                <input class="save" type="submit" name="changeInfo" value="Save"><br><br>
            </form>
        </div>
    </div>    

    <div id="password-tab" style="display: none;">
        <?php include 'admin-password-settings.php'; ?>
    </div>

    <script>

        function showTab(tabName) {
            if (tabName === 'account') {
                document.getElementById('account-tab').style.display = 'block';
                document.getElementById('password-tab').style.display = 'none';
                document.querySelector('.tab.active-tab').classList.remove('active-tab');
                document.querySelectorAll('.tab')[0].classList.add('active-tab');
            } else if (tabName === 'password') {
                document.getElementById('password-tab').style.display = 'block';
                document.getElementById('account-tab').style.display = 'none';
                document.querySelector('.tab.active-tab').classList.remove('active-tab');
                document.querySelectorAll('.tab')[1].classList.add('active-tab');
                }
        }

        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "no-image.webp";
            }
        }
    </script>
</body>
</html>