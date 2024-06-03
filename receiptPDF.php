<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delta";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];
} else {
    $orderID = 45;
}

// SQL query to retrieve order information along with customer and item details, grouped by orderID
$sql = "SELECT order_info.orderID, 
        accounts.name,
        accounts.emailaddress,
        accounts.phonenumber,
        order_info.orderAddress, 
        order_info.orderDate, 
        order_info.orderPWD,
        order_info.orderSeniorCitizen,
        order_info.orderTotal,
        order_info.orderstatus,
        payments.gcashName,
        payments.gcashNumber,
        payments.gcashReferenceNum
    FROM order_info 
    INNER JOIN order_items ON order_info.orderID = order_items.orderID 
    INNER JOIN accounts ON order_info.accountID = accounts.accountID 
    INNER JOIN items ON order_items.itemID = items.itemID 
    LEFT JOIN payments ON order_info.orderID = payments.orderID
    WHERE order_info.orderID = ?
    GROUP BY order_info.orderID";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

// Fetch order details
$orderDetails = $result->fetch_assoc();

$stmt->close();

// Retrieve items in the order
$sql_items = "SELECT items.itemName, items.itemPrice, order_items.itemAmount, order_items.totalPrice 
                FROM order_items 
                INNER JOIN items ON order_items.itemID = items.itemID 
                WHERE order_items.orderID = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $orderID);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

// Fetch all order items
$orderItems = [];
while ($row = $result_items->fetch_assoc()) {
    $orderItems[] = $row;
}

$stmt_items->close();
$conn->close();



require('fpdf/fpdf.php'); 

class PDF extends FPDF { 

	// Page header 
	function Header() { 
		
		// Add logo to page 
		$this->Image('images\DCT no bg.png', 55, 15, 100);
		
		// Set font family to Arial bold 
		$this->SetFont('Arial','B',20); 
		
		// Move to the right 
		$this->SetXY(10, 10); 
		
		// Header 
		$this->Cell(0, 30, '', 'LRT', 1, 'C'); 
	} 

	// Page footer 
	function Footer() { 
		
		// Position at 1.5 cm from bottom 
		$this->SetY(-15); 
		
		// Arial italic 8 
		$this->SetFont('Arial','I',8); 
		
		// Page number 
		$this->Cell(0,10,'Page ' . 
			$this->PageNo() . '/{nb}',0,0,'C'); 
	} 
} 

// Instantiation of FPDF class 
$pdf = new PDF(); 

// Define alias for number of pages 
$pdf->AliasNbPages(); 
$pdf->AddPage(); 


$pdf->SetFont('Times','B',20); 
$pdf->Cell(0, 15, 'ORDER '.$orderID.' RECEIPT', 'LRB', 1, 'C'); 

$pdf->SetFont('Times','B',20); 

//table header
$pdf->Cell(0, 5, '', 'LTR', 1, 'C');
$pdf->Cell(0, 10, 'ORDER INFORMATION', 'LR', 1, 'C');
$pdf->Cell(0, 5, '', 'LBR', 1, 'C');

$pdf->SetFont('Times','B',11);

$pdf->Cell(45, 10, 'Item Name', 1, 0, 'C'); 
$pdf->Cell(25, 10, 'Item Amount', 1, 0, 'C'); 
$pdf->Cell(30, 10, 'Price per Item', 1, 0, 'C'); 
$pdf->Cell(20, 10, 'Discount', 1, 0, 'C'); 
$pdf->Cell(35, 10, 'Discount price', 1, 0, 'C'); 
$pdf->Cell(35, 10, 'Subtotal', 1, 1, 'C'); 


$pdf->SetFont('Times','',12); 

$total = 0;

//loops through all the items in the order
foreach ($orderItems as $item) {
    $itemName = $item['itemName'];
    $itemAmount = $item['itemAmount'];
    $itemPrice = $item['itemPrice'];

    //checks the discount for the items
    $discount = 0;
    if ($itemAmount >= 200) {
        $discount = 0.2;
    } elseif ($itemAmount >= 100) {
        $discount = 0.1;
    } elseif ($itemAmount >= 50) {
        $discount = 0.05;
    } elseif ($itemAmount >= 25) {
        $discount = 0.025;
    }

    //calculations
    $subtotal = $itemPrice * $itemAmount;
    $discountAmount = $subtotal * $discount;
    $discountedSubtotal = $subtotal - $discountAmount;
    $total = $total + $discountedSubtotal;

    //table contents
    $pdf->Cell(45, 10, $itemName, 1, 0, 'C'); 
    $pdf->Cell(25, 10, $itemAmount, 1, 0, 'C'); 
    $pdf->Cell(30, 10, 'PHP '.number_format($itemPrice, 2), 1, 0, 'C'); 
    $pdf->Cell(20, 10, ($discount * 100).'%', 1, 0, 'C'); 
    $pdf->Cell(35, 10, 'PHP '.number_format($discountAmount, 2), 1, 0, 'C'); 
    $pdf->Cell(35, 10, 'PHP '.number_format($discountedSubtotal, 2), 1, 1, 'C');
}

//checks if customer is a pwd
$pwd = 0;
if ($orderDetails['orderPWD'] !== "PWD ID: NO PWD") {
    $pwd = 0.10;
}

//checks if customer is a senior citizen
$sc = 0;
if ($orderDetails['orderSeniorCitizen'] !== "Senior Citizen ID: NO SC") {
    $sc = 0.10;
}

//calculations
$pwdDiscount = $total * $pwd;
$scDiscount = $total * $sc;

$discounts = $pwdDiscount + $scDiscount;
$discountedTotal = $total - $discounts;

$vatTotal = $discountedTotal * 0.12;
$grandTotal = $discountedTotal + $vatTotal;

//total
$pdf->SetFont('Times','B',11); 
$pdf->Cell(155, 10, 'Total', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(35, 10, 'PHP '.number_format($total, 2), 1, 1, 'C'); 

//pwd discount
$pdf->SetFont('Times','B',11); 
$pdf->Cell(155, 10, 'PWD Discount (10%)', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(35, 10, '-PHP '.number_format($pwdDiscount, 2), 1, 1, 'C'); 

//sc discount
$pdf->SetFont('Times','B',11); 
$pdf->Cell(155, 10, 'Senior Citizen Discount (10%)', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(35, 10, '-PHP '.number_format($scDiscount, 2), 1, 1, 'C'); 

//vat total
$pdf->SetFont('Times','B',11); 
$pdf->Cell(155, 10, 'VAT (12%)', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(35, 10, 'PHP '.number_format($vatTotal, 2), 1, 1, 'C'); 

//grand total
$pdf->SetFont('Times','B',11); 
$pdf->Cell(155, 10, 'Grand Total', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(35, 10, 'PHP '.number_format($grandTotal, 2), 1, 1, 'C'); 



$pdf->SetFont('Times','B',20); 

//table header
$pdf->Cell(0, 5, '', 'LTR', 1, 'C');
$pdf->Cell(0, 10, 'CUSTOMER INFORMATION', 'LR', 1, 'C'); 
$pdf->Cell(0, 5, '', 'LBR', 1, 'C');

//customer address
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Customer Address', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['orderAddress'], 1, 1, 'C'); 

//customer pwd
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Customer PWD ID', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['orderPWD'], 1, 1, 'C'); 

//customer sc
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Customer Senior Citizen ID', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['orderSeniorCitizen'], 1, 1, 'C'); 

//customer gcash name
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Gcash Name', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['gcashName'], 1, 1, 'C'); 

//customer gcash number
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Gcash Number', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['gcashNumber'], 1, 1, 'C'); 

//gcash reference number
$pdf->SetFont('Times','B',11); 
$pdf->Cell(60, 10, 'Gcash Reference Number', 1, 0, 'C');

$pdf->SetFont('Times','',12); 
$pdf->Cell(130, 10, $orderDetails['gcashReferenceNum'], 1, 1, 'C'); 

$pdf->Output(); 

?>
