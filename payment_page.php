<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = mysqli_connect("localhost:3306", "root", "", "web2");

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name'];
} else {
    $user_name = '';
}

// Fetch the user's email from the database
$get_user_email = $conn->prepare("SELECT email FROM users WHERE name = ?");
$get_user_email->bind_param("s", $user_name); // Bind parameters
$get_user_email->execute();
$get_user_email->bind_result($user_email); // Bind result
$get_user_email->fetch(); // Fetch the result
$get_user_email->close(); // Close statement

// Fetch order details from the database
$transactionID = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';
$select_order = $conn->prepare("SELECT o.id, o.total_amount, o.user_name, oi.product_name, oi.quantity, oi.price, oi.total_price 
                                FROM orders o 
                                INNER JOIN order_items oi ON o.id = oi.order_id 
                                WHERE o.id = ?");
$select_order->bind_param("i", $transactionID); // Bind parameters
$select_order->execute();
$select_order->bind_result($order_id, $total_amount, $user_name, $product_name, $quantity, $price, $total_price); // Bind result

$update_order_status = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
$update_order_status->bind_param("i", $transactionID); // Bind parameters
$update_order_status->execute();
$update_order_status->close();

// Send email confirmation
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shopbee800@gmail.com';
    $mail->Password = 'fqjhitqjtddbxqvz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('shopbee800@gmail.com', 'Shopbee');
    
    if (!empty($user_email)) {
        $mail->addAddress($user_email);
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation';

        $emailBody = '<div style="padding: 20px; font-family: Arial, sans-serif; color: #333;">';
$emailBody .= '<div style="border-bottom: 2px solid #007BFF; margin-bottom: 20px; padding-bottom: 10px;">';
$emailBody .= '<h2 style="color: #007BFF;">Order Confirmation</h2>';
$emailBody .= '<p>Thank you for your purchase! Your payment has been successfully received.</p>';
$emailBody .= '</div>';
$emailBody .= '<h3 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">Order Details</h3>';
$emailBody .= '<table style="width: 100%; border-collapse: collapse;">';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Transaction ID:</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">' . $order_id . '</td>';
$emailBody .= '</tr>';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Total Amount Paid:</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">&#8369;' . $total_amount . '</td>';
$emailBody .= '</tr>';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Product Name</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">' . $product_name . '</td>';
$emailBody .= '</tr>';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Quantity</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">' . $quantity . '</td>';
$emailBody .= '</tr>';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Price</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">' . $price . '</td>';
$emailBody .= '</tr>';
$emailBody .= '<tr>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"><strong>Total Price</strong></td>';
$emailBody .= '<td style="padding: 10px; border: 1px solid #ccc;">' . $total_price . '</td>';
$emailBody .= '</tr>';
$emailBody .= '</table>';
$emailBody .= '<div style="border-top: 1px solid #ccc; padding-top: 10px; text-align: center;">';
$emailBody .= '<p style="margin: 0;">If you have any questions, please contact our support team.</p>';
$emailBody .= '<p style="margin: 0;">Thank you for shopping with us!</p>';
$emailBody .= '</div>';
$emailBody .= '</div>';

        // Set the email body
        $mail->Body = $emailBody;
        // Send the email
        $mail->send();
    } else {
        echo 'User email is not populated.';
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Display order details and success message
echo '<div style="border: 2px solid #ddd; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; margin: 50px auto; max-width: 600px;">';
echo '<h2 style="color: #333;">Order Placed</h2>';
echo '<p style="color: #555;">Transaction ID: ' . $order_id . '</p>';
echo '<p style="color: #555;">Total Amount Paid: &#8369;' . $total_amount . '</p>';
echo '<p style="color: #555;">Order Status: Approved</p>'; // Display the updated order status
echo '<p style="color: #555;">A confirmation email has been sent to your email address.</p>';
echo '<a href="home.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: #fff; text-decoration: none; border-radius: 5px;">Return Home</a>';
echo '</div>';
?>
