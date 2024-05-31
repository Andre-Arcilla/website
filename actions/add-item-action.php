<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "delta";

    // Connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $newItemId = $_POST['new_item_id'];
    $newItemName = $_POST['new_item_name'];
    $newItemPrice = $_POST['new_item_price'];
    $newItemStock = $_POST['new_item_stock'];

    // Insert the new item into the database
    $sql = "INSERT INTO items (itemID, itemName, itemPrice, itemStock) VALUES ('$newItemId', '$newItemName', '$newItemPrice', '$newItemStock')";

    if (mysqli_query($conn, $sql)) {
        echo "New item added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    
    header("Location: ../admin pages/adminEditItems.php");
    exit();
}
?>