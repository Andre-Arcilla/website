<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rartest";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$trackingnum = '0928123'; // Example tracking number

// Check if the 'item' key exists in $_POST array
if (isset($_POST['item'])) {
    $item = $_POST['item'];
    $price = $_POST['price'];
    $bulk = isset($_POST['bulk']) ? $_POST['bulk'] : 'N';
    $amount = $_POST['amount'];
    
    // Insert item into the cart table
    $sql = "INSERT INTO cart (trackingNum, itemName, bulk, itemAmount, price) VALUES ('$trackingnum', '$item', '$bulk', '$amount', '$price')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Item added to cart.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
