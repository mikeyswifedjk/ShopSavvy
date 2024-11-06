<?php include('admin-navigation.php'); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost:3306", "root", "", "web2");

// Function to validate if a value is a valid integer
function isValidInteger($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

// Check if the form is submitted to create a new seller
if (isset($_POST["submit"])) {
    $fname = mysqli_real_escape_string($conn, $_POST["fname"]);
    $mname = mysqli_real_escape_string($conn, $_POST["mname"]);
    $lname = mysqli_real_escape_string($conn, $_POST["lname"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $number = mysqli_real_escape_string($conn, $_POST["number"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = 'seller';  // Default password
    $confirm_password = 'seller';  // Default confirm password

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO seller (fname, mname, lname, address, number, username, email, password, blocked) VALUES ('$fname', '$mname', '$lname', '$address', '$number', '$username', '$email', '$hashed_password', 0)";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Seller added successfully');</script>";
    } else {
        echo "<script>alert('Error adding record: " . mysqli_error($conn) . "');</script>";
    }
}

// Delete Selected Sellers
if (isset($_POST["delete_selected"])) {
    if (isset($_POST["selected_sellers"]) && is_array($_POST["selected_sellers"])) {
        foreach ($_POST["selected_sellers"] as $selectedSellerId) {
            $deleteQuery = "DELETE FROM seller WHERE seller_id = $selectedSellerId";
            if (!mysqli_query($conn, $deleteQuery)) {
                echo "<script>alert('Error deleting record: " . mysqli_error($conn) . "');</script>";
            }
        }
        echo "<script>alert('Selected sellers deleted successfully');</script>";
    } else {
        echo "<script>alert('No sellers selected for deletion');</script>";
    }
}

// Block/Unblock Seller
if (isset($_POST["block_seller"])) {
    $sellerId = mysqli_real_escape_string($conn, $_POST["seller_id"]);
    $query = "UPDATE seller SET blocked = 1 WHERE seller_id = $sellerId";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Seller blocked successfully');</script>";
    } else {
        echo "<script>alert('Error blocking seller: " . mysqli_error($conn) . "');</script>";
    }
}

if (isset($_POST["unblock_seller"])) {
    $sellerId = mysqli_real_escape_string($conn, $_POST["seller_id"]);
    $adminUsername = mysqli_real_escape_string($conn, $_POST["admin_username"]);

    // Verify admin username (assume admin usernames are stored in the admin table)
    $adminQuery = "SELECT * FROM admin WHERE username = '$adminUsername'";
    $adminResult = mysqli_query($conn, $adminQuery);

    if (mysqli_num_rows($adminResult) > 0) {
        $query = "UPDATE seller SET blocked = 0 WHERE seller_id = $sellerId";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Seller unblocked successfully');</script>";
        } else {
            echo "<script>alert('Error unblocking seller: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid admin username');</script>";
    }
}

// Define a variable for the search term
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$searchQuery = "SELECT * FROM seller WHERE fname LIKE '%$searchTerm%' OR lname LIKE '%$searchTerm%' OR username LIKE '%$searchTerm%'";
$result = mysqli_query($conn, $searchQuery);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png"/>
    <link rel="stylesheet" type="text/css" href="css/crud-seller.css">
    <title>Seller Management</title>
    <script>
        function unblockSeller(sellerId) {
            var adminUsername = prompt("Please enter admin username to unblock this seller:");
            if (adminUsername != null) {
                document.getElementById('unblock_seller_id').value = sellerId;
                document.getElementById('admin_username').value = adminUsername;
                document.getElementById('unblockForm').submit();
            }
        }
    </script>
</head>
<body>
    <h1 class="text1"> SELLER MANAGEMENT </h1>
    <div class="all">
        <div class="add">
            <form action="" method="post" autocomplete="off">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" required placeholder="Enter first name"><br><br>
                
                <label for="mname">Middle Name:</label>
                <input type="text" name="mname" id="mname" required placeholder="Enter middle name"><br><br>
                
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" required placeholder="Enter last name"><br><br>
                
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required placeholder="Enter address"><br><br>
                
                <label for="number">Phone Number:</label>
                <input type="text" name="number" id="number" required placeholder="Enter phone number"><br><br>
                
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required placeholder="Enter username"><br><br>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required placeholder="Enter email"><br><br>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="seller" required placeholder="Enter password"><br><br>
                
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" value="seller" required placeholder="Confirm password"><br><br>
                
                <button type="submit" name="submit" class="btnSubmit">Submit</button>
            </form>
        </div> <!-- add -->

        <div class="view">
            <!-- Search Form -->
            <form action="" method="get" class="searchForm">
                <h2 class="text4">SELLER LIST</h2>
                <input type="text" name="search" class="searchtxt" id="search" placeholder="Search by name or username" required />
                <button type="submit" class="btnSearch">
                    <i class="fa-solid fa-magnifying-glass" style="color: #502779;"></i>
                </button>
            </form><br>

            <form action="" method="POST">
                <button type="submit" name="delete_selected" class="deletebtn">
                    <i class="fa-solid fa-trash" style="color: #AD53A6"></i>
                </button>
                <table border="1" cellspacing="0" cellpadding="10">
                    <tr class="thView">
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Block</th>
                        <th>Unblock</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['seller_id']; ?></td>
                        <td><?php echo $row['fname']; ?></td>
                        <td><?php echo $row['mname']; ?></td>
                        <td><?php echo $row['lname']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['number']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <?php if ($row['blocked'] == 0): ?>
                            <form action="" method="post">
                                <input type="hidden" name="seller_id" value="<?php echo $row['seller_id']; ?>">
                                <button type="submit" id="block-btn" name="block_seller">Block</button>
                            </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['blocked'] == 1): ?>
                            <button type="button" id="unblock-btn" onclick="unblockSeller(<?php echo $row['seller_id']; ?>)">Unblock</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <input type="checkbox" name="selected_sellers[]" value="<?php echo $row['seller_id']; ?>">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </form>
        </div>
    </div> 

    <form id="unblockForm" action="" method="post" style="display: none;">
        <input type="hidden" name="seller_id" id="unblock_seller_id">
        <input type="hidden" name="admin_username" id="admin_username">
        <input type="hidden" name="unblock_seller" value="1">
    </form>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
