<?php
    session_start();

    // Check if the usertype is not set or is not 'admin'
    if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] != 'admin') {
        // Redirect to the login page
        header("Location: ../login.php");
        exit(); // Stop further execution
    }

    // Check if the item ID is provided in the URL
    if(isset($_GET['itemId'])) {
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

        // Escape special characters to prevent SQL injection
        $itemId = mysqli_real_escape_string($conn, $_GET['itemId']);

        // Delete associated records with itemID from other tables
        $deleteOrderItemsSql = "DELETE FROM order_items WHERE itemID = '$itemId'";
        $deleteOrderItemsResult = mysqli_query($conn, $deleteOrderItemsSql);

        // Check if deletion of associated records was successful
        if ($deleteOrderItemsResult) {
            // Delete the item itself
            $deleteItemSql = "DELETE FROM items WHERE itemID = '$itemId'";
            $deleteItemResult = mysqli_query($conn, $deleteItemSql);

            // Check if deletion of item was successful
            if ($deleteItemResult) {
                // If deletion is successful, redirect back to the same page
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // If deletion of item fails, display error message
                echo "Error deleting item: " . mysqli_error($conn);
            }
        } else {
            // If deletion of associated records fails, display error message
            echo "Error deleting associated records: " . mysqli_error($conn);
        }

        // Close database connection
        mysqli_close($conn);
    } else {
        // If item ID is not provided, redirect to the admin dashboard
        header("Location: ../admin pages/adminEditItems.php");
        exit();
    }
?>
