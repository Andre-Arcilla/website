<?php
// Check if success parameter is set
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p>Update successful!</p>";
}
?>

<!DOCTYPE html>
<head>
    <title>admin page</title>
    <style>
        /* CSS styles */
        body {
            background-color: hsl(180, 12%, 45%);
            margin-top: 5vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        a {
            color: black;
            text-decoration: none !important;
            font-size: 1.5vw;
        }

        table {
            width: 60vw;
        }

        th, td {
            padding: .5vw;
            font-size: 2vw;
            width: 12.5vw;
            text-align: center;
        }

        input[type="text"] {
            font-size: 1.5vw;
            width: 12.5vw;
            text-align: center;
        }

        input[type="submit"] {
            font-size: 1.5vw;
            width: 12vw;
            height: 3vw;
        }
    </style>
</head>
<body>
    <?php

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dct";

        // Connect to the database
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to select all items
        $sql = "SELECT * FROM items";
        $result = mysqli_query($conn, $sql);
    ?>

    <!-- Form to update items -->
    <form method="post" action="update_items.php">
        <table border="1vw">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Bulk Price</th>
                    <th>Item Bulk Amount</th>
                    <th>Item Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Display items in table rows
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='5'>No rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <!-- Input fields for each item -->
                                    <td><input type='text' name='itemID[]' value='{$row['itemID']}' readonly></td>
                                    <td><input type='text' name='itemName[]' value='{$row['itemName']}'></td>
                                    <td><input type='text' name='itemPrice[]' value='{$row['itemPrice']}'></td>
                                    <td><input type='text' name='bulkPrice[]' value='{$row['bulkPrice']}'></td>
                                    <td><input type='text' name='bulkAmount[]' value='{$row['bulkAmount']}'></td>
                                    <td><input type='text' name='itemStock[]' value='{$row['itemStock']}'></td>
                                </tr>\n
                            ";
                        }
                    }

                    
                // Close database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>

        <br>

        <!-- Submit button to update items -->
        <input type="submit" value="Update">
    </form>

    <br>

    <!-- Return link -->
    <a href="adminmain.php">[RETURN]</a>
</body>
</html>
