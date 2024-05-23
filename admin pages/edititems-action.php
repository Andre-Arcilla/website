<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delta";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set
    if (isset($_POST['itemID']) && isset($_POST['itemName']) && isset($_POST['itemPrice']) && isset($_POST['soldAmount']) && isset($_POST['itemStock'])) {
        // Loop through the submitted data
        for ($i = 0; $i < count($_POST['itemID']); $i++) {
            // Escape user inputs for security
            $itemID = mysqli_real_escape_string($conn, $_POST['itemID'][$i]);
            $itemName = mysqli_real_escape_string($conn, $_POST['itemName'][$i]);
            $itemPrice = mysqli_real_escape_string($conn, $_POST['itemPrice'][$i]);
            $soldAmount = mysqli_real_escape_string($conn, $_POST['soldAmount'][$i]);
            $itemStock = mysqli_real_escape_string($conn, $_POST['itemStock'][$i]);

            // Update query
            $sql = "UPDATE items SET itemName='$itemName', itemPrice='$itemPrice', soldAmount='$soldAmount', itemStock='$itemStock' WHERE itemID='$itemID'";

            if (!mysqli_query($conn, $sql)) {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
        
        // Close the database connection
        mysqli_close($conn);

        // Redirect back to the previous page with success parameter
        header("Location: edititems.php?success=1");
        exit();
    } else {
        echo "Form fields are not set";
    }
} else {
    echo "Form not submitted";
}
?>
