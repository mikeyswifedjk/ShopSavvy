<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_clean();

require_once('TCPDF/tcpdf.php');

$pdf = new TCPDF('l', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MAIKA ORDONEZ');
$pdf->SetTitle('Sample Generated Report');
$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', 'BI', 9);
$pdf->AddPage();

$startDate = isset($_GET['start-date']) ? $_GET['start-date'] : date('Y-m-d');
$endDate = isset($_GET['end-date']) ? $_GET['end-date'] : date('Y-m-d');

$html = '
<h1>Payment History Report</h1>
<p>Date Range: ' . $startDate . ' to ' . $endDate . '</p>
<hr>
<h2>Orders Table</h2>

<table border="1">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User Name</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Region ID</th>
            <th>Discount Code</th>
            <th>Payment Method</th>
            <th>Total Amount</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>';

$dbConnection = mysqli_connect("localhost:3306", "root", "", "web2");

if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM orders WHERE order_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
$result = mysqli_query($dbConnection, $query);

while ($row = mysqli_fetch_array($result)) {
    $html .= '
        <tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['user_name'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['phone'] . '</td>
            <td>' . $row['address'] . '</td>
            <td>' . $row['region_id'] . '</td>
            <td>' . $row['discount_code'] . '</td>
            <td>' . $row['payment_method'] . '</td>
            <td>' . $row['total_amount'] . '</td>
            <td>' . $row['order_date'] . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

// Fetch data for order items
$html .= '<h2>Order Items Table</h2>';
$html .= '<table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Product Image</th>
                </tr>
            </thead>
            <tbody>';

$queryItems = "SELECT * FROM order_items";
$resultItems = mysqli_query($dbConnection, $queryItems);

while ($rowItem = mysqli_fetch_array($resultItems)) {
    $html .= '
        <tr>
            <td>' . $rowItem['id'] . '</td>
            <td>' . $rowItem['order_id'] . '</td>
            <td>' . $rowItem['product_name'] . '</td>
            <td>' . $rowItem['quantity'] . '</td>
            <td>' . $rowItem['price'] . '</td>
            <td>' . $rowItem['total_price'] . '</td>
            <td><img src="' . $rowItem['product_image'] . '" height="50px"></td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

$pdf->writeHTML($html);

ob_end_clean();

$pdf->Output('usersreport.pdf', 'I');
?>
