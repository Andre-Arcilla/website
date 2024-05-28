<?php
session_start();

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

// Check if the checkout form was submitted
if (isset($_POST["checkout"])) {
    // Get GCash details from the form
    $gcashName = $_POST["gcashName"];
    $gcashNumber = $_POST["gcashNumber"];
    $gcashReferenceNum = $_POST["gcashReferenceNum"];
    $address = $_POST["address"];
    $total = $_POST["total"];


    // Get customer information from the accounts table based on session
    $userID = $_SESSION['userid']; // Assuming you have stored the user's ID in the session
    $customerInfoQuery = "SELECT * FROM accounts WHERE accountID = $userID";
    $customerInfoResult = $conn->query($customerInfoQuery);
    $customerInfo = $customerInfoResult->fetch_assoc();

    // Insert order information into order_info table
    $orderDate = date("Y-m-d");
    $insertOrderQuery = "INSERT INTO order_info (accountID, orderAddress, orderDate, orderTotal) 
                        VALUES ('$userID', '$address', '$orderDate', '$total')";
    if ($conn->query($insertOrderQuery) === TRUE) {
        $orderID = $conn->insert_id; // Get the last inserted orderID
        foreach ($_SESSION["cart"] as $itemID => $quantity) {
            // Calculate total price for each item and update order total
            $itemPriceQuery = "SELECT itemPrice FROM items WHERE itemID = '$itemID'";
            $itemPriceResult = $conn->query($itemPriceQuery);
            $itemPrice = $itemPriceResult->fetch_assoc()['itemPrice'];
            $totalPrice = $itemPrice * $quantity;

            // Insert item into order_items table
            $insertItemQuery = "INSERT INTO order_items (orderID, itemID, itemAmount, totalPrice) 
                                VALUES ('$orderID', '$itemID', '$quantity', '$totalPrice')";
            $conn->query($insertItemQuery);

            // Reduce the item stock in the items table
            $reduceStockQuery = "UPDATE items SET itemStock = itemStock - '$quantity' WHERE itemID = '$itemID'";
            $conn->query($reduceStockQuery);

            // Update the soldAmount column in the items table
            $updateSoldAmountQuery = "UPDATE items SET soldAmount = soldAmount + '$quantity' WHERE itemID = '$itemID'";
            $conn->query($updateSoldAmountQuery);
        }

        // Insert payment details into payments table
        $insertPaymentQuery = "INSERT INTO payments (orderID, gcashName, gcashNumber, gcashReferenceNum) 
                                VALUES ('$orderID', '$gcashName', '$gcashNumber', '$gcashReferenceNum')";
        $conn->query($insertPaymentQuery);

        // Clear the session's cart
        unset($_SESSION["cart"]);

        // Redirect to success page
        header("Location: ../index.php?success=1");
        exit();
    } else {
        echo "Error: " . $insertOrderQuery . "<br>" . $conn->error;
    }
}
?>
