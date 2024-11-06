<?php include('customer-navigation.php'); ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_name'])) {
    header("Location: http://localhost/web2/login.php");
    exit;
}

$userName = $_SESSION['user_name'];

$userQuery = "SELECT id FROM users WHERE name = ?";
$userStatement = mysqli_prepare($dbConnection, $userQuery);
mysqli_stmt_bind_param($userStatement, "s", $userName);
mysqli_stmt_execute($userStatement);
$userResult = mysqli_stmt_get_result($userStatement);

if (!$userResult) {
    die("Error in user SQL query: " . mysqli_error($dbConnection));
}

$userRow = mysqli_fetch_assoc($userResult);
$user_id = isset($userRow['id']) ? $userRow['id'] : 0;

$cartCountQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
$cartCountStatement = mysqli_prepare($dbConnection, $cartCountQuery);
mysqli_stmt_bind_param($cartCountStatement, "i", $user_id);
mysqli_stmt_execute($cartCountStatement);
$cartCountResult = mysqli_stmt_get_result($cartCountStatement);

if (!$cartCountResult) {
    die("Error in cart count SQL query: " . mysqli_error($dbConnection));
}

$cartCountRow = mysqli_fetch_assoc($cartCountResult);
$cartCount = isset($cartCountRow['count']) ? $cartCountRow['count'] : 0;

if (isset($_POST['checkout_items'])) {
    if (!empty($_POST['selected_items'])) {
        $_SESSION['selected_items'] = $_POST['selected_items'];
        header("Location: checkout.php?user=$userName");
        exit;
    }
}

if (isset($_POST['delete_items'])) {
    if (!empty($_POST['selected_items'])) {
        $selectedItems = explode("-", $_POST['selected_items']);
        foreach ($selectedItems as $selectedItem) {
            $selectedItem = intval($selectedItem);

            $deleteQuery = "DELETE FROM cart WHERE id = ? AND user_id = ?";
            $deleteStatement = mysqli_prepare($dbConnection, $deleteQuery);
            mysqli_stmt_bind_param($deleteStatement, "ii", $selectedItem, $user_id);
            $deleteResult = mysqli_stmt_execute($deleteStatement);

            if (!$deleteResult) {
                die("Error deleting item from cart: " . mysqli_error($dbConnection));
            }
        }

        header("Location: cart.php?user=$userName");
        exit;
    }
}

if (isset($_POST['update_quantity'])) {
    foreach ($_POST['quantities'] as $itemId => $quantity) {
        $quantity = intval($quantity);

        // Fetch the price for the current item
        $priceQuery = "SELECT price FROM cart WHERE id = ? AND user_id = ?";
        $priceStatement = mysqli_prepare($dbConnection, $priceQuery);
        mysqli_stmt_bind_param($priceStatement, "ii", $itemId, $user_id);
        mysqli_stmt_execute($priceStatement);
        $priceResult = mysqli_stmt_get_result($priceStatement);

        if (!$priceResult) {
            die("Error fetching price: " . mysqli_error($dbConnection));
        }

        $priceRow = mysqli_fetch_assoc($priceResult);
        $price = isset($priceRow['price']) ? $priceRow['price'] : 0.0;
        $totalPrice = $quantity * $price;

        $updateQuery = "UPDATE cart SET quantity = ?, total_price = ? WHERE id = ? AND user_id = ?";
        $updateStatement = mysqli_prepare($dbConnection, $updateQuery);
        mysqli_stmt_bind_param($updateStatement, "idii", $quantity, $totalPrice, $itemId, $user_id);
        $updateResult = mysqli_stmt_execute($updateStatement);

        if (!$updateResult) {
            die("Error updating item quantity: " . mysqli_error($dbConnection));
        }
    }

    header("Location: cart.php?user=$userName");
    exit;
}

$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1";
$resultSettings = $dbConnection->query($sqlGetSettings);

if (!$resultSettings) {
    die("Error fetching design settings: " . mysqli_error($dbConnection));
}

if ($resultSettings->num_rows > 0) {
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
    <title>SHOPSAVVY CART</title>
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>
    <div class="cart-container">
        <div class="row-fields">
            <p>Action</p>
            <p>Image</p>
            <p>Product</p>
            <p>Quantity</p>
            <p>Unit Price</p>
            <p>Total Price</p>
        </div>
        <form method="POST" action="cart.php?user=<?= $userName ?>">
            <input type="hidden" name="selected_items" id="selected_items" value="">

            <?php
            $totalQuantity = 0;
            $totalPrice = 0;

            $cartItemsQuery = "SELECT c.id, c.product_name, c.quantity, c.price, c.product_image, c.total_price FROM cart c WHERE c.user_id = ?";
            $cartItemsStatement = mysqli_prepare($dbConnection, $cartItemsQuery);
            mysqli_stmt_bind_param($cartItemsStatement, "i", $user_id);
            mysqli_stmt_execute($cartItemsStatement);
            $cartItemsResult = mysqli_stmt_get_result($cartItemsStatement);

            if (!$cartItemsResult) {
                die("Error fetching cart items: " . mysqli_error($dbConnection));
            }

            while ($row = mysqli_fetch_assoc($cartItemsResult)) {
                $itemId = $row['id'];
                $productName = $row['product_name'];
                $quantity = $row['quantity'];
                $unitPrice = $row['price'];
                $productImage = $row['product_image'];
                $itemTotalPrice = $row['total_price'];

                $totalQuantity += $quantity;
                $totalPrice += $itemTotalPrice;

                echo '<div class="row-fields" data-item-id="' . $itemId . '" data-quantity="' . $quantity . '" data-total-price="' . $itemTotalPrice . '">';
                echo '<input type="checkbox" class="item-checkbox" name="item[]" value="' . $itemId . '">';
                echo '<p><img src="' . $productImage . '" alt="' . $productName . '" width="50" height="50" /></p>';
                echo '<p>' . $productName . '</p>';
                echo '<p><input type="number" class="quantity-input" name="quantities[' . $itemId . ']" value="' . $quantity . '" min="1"></p>';
                echo '<p>₱' . $unitPrice . '</p>';
                echo '<p>₱' . $itemTotalPrice . '</p>';
                echo '</div>';
            }

            echo '<div class="row-fields" id="totals-row">';
            echo '<p></p>';
            echo '<p>Total:</p>';
            echo '<p id="total-quantity">' . $totalQuantity . '</p>';
            echo '<p></p>';
            echo '<p id="total-price">₱' . $totalPrice . '</p>';
            echo '<p></p>';
            echo '</div>';

            echo '<div class="cart-actions">';
            echo '<button type="submit" name="delete_items"><i class="fas fa-trash-alt"></i></button>';
            echo '<button type="submit" name="checkout_items"><i class="fas fa-shopping-cart"></i></button>';
            echo '<button type="submit" name="update_quantity"><i class="fa-solid fa-pen-to-square"></i></button>';
            echo '</div>';
            ?>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectedItemsInput = document.getElementById('selected_items');
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const totalQuantityElement = document.getElementById('total-quantity');
            const totalPriceElement = document.getElementById('total-price');

            function updateTotals() {
                let totalQuantity = 0;
                let totalPrice = 0;

                document.querySelectorAll('.row-fields').forEach(function (row) {
                    const isChecked = row.querySelector('.item-checkbox').checked;
                    if (isChecked) {
                        totalQuantity += parseInt(row.dataset.quantity);
                        totalPrice += parseFloat(row.dataset.totalPrice);
                    }
                });

                totalQuantityElement.textContent = totalQuantity;
                totalPriceElement.textContent = '₱' + totalPrice.toFixed(2);
            }

            document.querySelector('form').addEventListener('submit', function (event) {
                const selectedItems = [];
                document.querySelectorAll('.item-checkbox:checked').forEach(function (checkbox) {
                    selectedItems.push(checkbox.value);
                });
                selectedItemsInput.value = selectedItems.join('-');
            });

            quantityInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    updateTotals();
                });
            });

            updateTotals();
        });
    </script>

    <?php include('customer-footer.php'); ?>
</body>
</html>

<?php
mysqli_close($dbConnection);
?>
