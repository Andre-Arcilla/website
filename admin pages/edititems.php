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
        body {
            background-color: hsl(180, 12%, 45%);
            margin-top: 5vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('../images/backgrounds/bg2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        table {
            width: 60vw;
            border-collapse: collapse; /* Ensure borders are collapsed into a single border */
            margin-top: 1rem; /* Add margin to separate table from header */
            background-color: lightblue; /* Optional: Change background color to distinguish from table body */
        }

        th, td, #customer {
            padding: .5vw;
            font-size: 1.5vw;
            border: 1px solid black; /* Set border for cells */
        }

        thead {
            position: sticky;
            top: 0; /* Stick the header row to the top of the viewport */
            background-color: deepskyblue; /* Optional: Change background color to distinguish from table body */
        }

        a {
            color: black;
            text-decoration: none !important;
            font-size: 1.5vw;
            margin-bottom: 5rem;
        }
    </style>
</head>
<body>
    <?php

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

        // Query to select all items
        $sql = "SELECT * FROM items";
        $result = mysqli_query($conn, $sql);
    ?>

    <!-- Form to update items -->
    <form method="post" action="edititems-action.php">
        <table border="1vw">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Stock</th>
                    <th>Amount Sold</th>
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
                                    <td><input type='text' name='itemStock[]' value='{$row['itemStock']}'></td>
                                    <td><input type='text' name='soldAmount[]' value='{$row['soldAmount']}'></td>
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
