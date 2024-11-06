<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('TCPDF/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MAIKA ORDONEZ');
$pdf->SetTitle('Sample Generated Report');

// Disable header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Create HTML content
$html = '
<h1>Product Inventory Report</h1>
<hr>
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Product Type</th>
            <th>Product Brand</th>
            <th>Product Price</th>
            <th>Product Quantity</th>
            <th>Product Sales</th>
            <th>Stocks Available</th>
        </tr>
    </thead>
    <tbody>';

$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

$selectProductsQuery = "SELECT * FROM products";
$productsResult = mysqli_query($dbConnection, $selectProductsQuery);

if (!$productsResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

while ($product = mysqli_fetch_assoc($productsResult)) {
    $stockAvailability = $product['product_stocks'] - $product['product_sales'];
    $html .= '
        <tr>
            <td>' . $product['product_id'] . '</td>
            <td>' . $product['product_name'] . '</td>
            <td>' . $product['product_type'] . '</td>
            <td>' . $product['product_brand'] . '</td>
            <td>' . $product['product_price'] . '</td>
            <td>' . $product['product_stocks'] . '</td>
            <td>' . $product['product_sales'] . '</td>
            <td>' . $stockAvailability . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

mysqli_close($dbConnection); // Close the database connection

$pdf->writeHTML($html);
$pdf->Output('product_inventory_report.pdf', 'I');
?>