<?php
require_once 'mysql_connect.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        header('Content-Type: application/xml');
        echo '<product>';
        foreach ($product as $key => $value) {
            echo "<$key>$value</$key>";
        }
        echo '</product>';
    } else {
        http_response_code(404);
        echo '<error>Product not found</error>';
    }
} else {
    http_response_code(400);
    echo '<error>No product ID provided</error>';
}
?>
