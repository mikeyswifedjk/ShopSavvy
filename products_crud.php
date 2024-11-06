<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'mysql_connect.php';
$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/xml');

switch($method){
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $xml = new SimpleXMLElement('<products></products>');
        foreach ($products as $product) {
            $productElement = $xml->addChild('product');
            foreach ($product as $key => $value) {
                $productElement->addChild($key, $value);
            }
        }

        echo $xml->asXML();
        break;

    case 'POST':
        $xmlData = file_get_contents('php://input');
        $data = simplexml_load_string($xmlData);
    
        if ($data === false) {
            http_response_code(400);
            echo '<response><error>Invalid XML data</error></response>';
            exit;
        }
    
        $requiredFields = ['product_name', 'product_type', 'product_brand', 'product_image', 'product_description', 'product_price', 'product_stocks'];
        foreach ($requiredFields as $field) {
            if (!isset($data->$field) || empty($data->$field)) {
                http_response_code(400);
                echo '<response><error>Missing required field: ' . $field . '</error></response>';
                exit;
            }
        }
    
        // Check if product_name already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM `products` WHERE `product_name` = ?");
        $stmt->execute([$data->product_name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            http_response_code(400);
            echo '<response><error>Product with the same name already exists</error></response>';
            exit;
        }
    
        // Check if product_price is a valid number
        if ($data->product_price < 0) {
            http_response_code(400);
            echo '<response><error>Price must not be 0</error></response>';
            exit;
        }
    
        // Insert the product into the database
        $stmt = $pdo->prepare("INSERT INTO `products`(`product_name`, `product_type`, `product_brand`, `product_image`, `product_description`, `product_price`, `product_stocks`, `product_sales`) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$data->product_name, $data->product_type, $data->product_brand, $data->product_image, $data->product_description, $data->product_price, $data->product_stocks, 0]);
    
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo '<response><message>Product Added!</message></response>';
        } else {
            http_response_code(500);
            echo '<response><error>Error adding product.</error></response>';
        }
        break;
        

    case 'PUT':
        $xmlData = file_get_contents('php://input');
        $data = simplexml_load_string($xmlData);
    
        if ($data === false) {
            http_response_code(400);
            echo '<response><error>Invalid XML data</error></response>';
            exit;
        }
    
        $requiredFields = ['product_id', 'product_name', 'product_type', 'product_brand', 'product_description', 'product_price'];
        foreach ($requiredFields as $field) {
            if (!isset($data->$field) || empty($data->$field)) {
                http_response_code(400);
                echo '<response><error>Missing required field: ' . $field . '</error></response>';
                exit;
            }
        }
    
        if ($data->product_price <= 0) {
            http_response_code(400);
            echo '<response><error>Price must be greater than 0</error></response>';
            exit;
        }
    
        // Check if product name is changed
        $stmt = $pdo->prepare("SELECT `product_name`, `product_image` FROM `products` WHERE `product_id` = ?");
        $stmt->execute([$data->product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $existingProductName = $product['product_name'];
        $existingProductImage = $product['product_image'];
    
        // Check if the name has changed and if it's not empty
        if ($existingProductName !== $data->product_name && !empty($data->product_name)) {
            // Check if the new name is a duplicate
            $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM products WHERE product_name = ? AND product_id <> ?");
            $stmt->execute([$data['product_name'], $data['product_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['count'] > 0) {
                http_response_code(400);
                echo '<response><error>Product with the same name already exists</error></response>';
                exit;
            }
        }
    
        // Check if there's a new uploaded image
        $newImage = $existingProductImage;
        if (isset($data->product_image) && !empty($data->product_image)) {
            $newImage = $data->product_image;
        }
    
        $stmt = $pdo->prepare("UPDATE `products` SET `product_name`=?, `product_type`=?, `product_brand`=?, `product_image`=?, `product_description`=?, `product_price`=?, `product_stocks`=? WHERE `product_id`=?");
        $stmt->execute([$data->product_name, $data->product_type, $data->product_brand, $newImage, $data->product_description, $data->product_price, $data->product_stocks, $data->product_id]);
    
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo '<response><message>Product Updated!</message></response>';
        } else {
            http_response_code(500);
            echo '<response><error>Error updating product.</error></response>';
        }
        break;

    case 'DELETE':
        $xmlData = file_get_contents('php://input');
        $data = simplexml_load_string($xmlData);
    
        if ($data === false || !isset($data->product_id)) {
            http_response_code(400);
            echo '<response><error>Invalid XML data or missing required field product_id</error></response>';
            exit;
        }
    
        $productIds = [];
        foreach ($data->product_id as $productId) {
            $productIds[] = (string)$productId;
        }
    
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $pdo->prepare("UPDATE `products` SET `product_stocks` = 0 WHERE `product_id` IN ($placeholders)");
        $stmt->execute($productIds);
    
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo '<response><message>Products put off-sale!</message></response>';
        } else {
            http_response_code(500);
            echo '<response><error>Error putting products off-sale.</error></response>';
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Invalid Method']);
        break;
}
?>