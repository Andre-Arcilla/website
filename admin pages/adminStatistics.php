<?php
    session_start();

    // Check if the usertype is not set or is not 'admin'
    if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] != 'admin') {
        // Redirect to the login page
        header("Location: ../login.php");
        exit(); // Stop further execution
    }

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminStatistics.css">
</head>
<body>
    <header>
        <img src="..\images\DCT no bg v3.png">
        <div class="main-website"><a href="#">back to main website</a></div>
    </header>
    <div class="sidebar">
        <button class="sidebar-button" id="hidden">aaaa</button>
        <button class="sidebar-button" id="selected">statistics</button>
        <button class="sidebar-button" onclick="location.href='adminViewItems.php';">view items</button>
        <button class="sidebar-button" onclick="location.href='adminEditItems.php';">edit items</button>
        <button class="sidebar-button" onclick="location.href='adminViewOrders.php';">view orders</button>
    </div>
    <div class="main-content">
        <table>
            <thead>
                <tr class="row1">
                    <th colspan="3">Order By Amount Sold</th>
                </tr>
                <tr class="row2">
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Amount Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Query to select all items ordered by soldAmount in descending order
                    $sql = "SELECT * FROM items ORDER BY soldAmount DESC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='3'>No rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <td>{$row['itemID']}</td>
                                    <td>{$row['itemName']}</td>
                                    <td>{$row['soldAmount']}</td>
                                </tr>\n
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>
        <table>
            <thead>
                <tr class="row1">
                    <th colspan="3">Order By Item Stock</th>
                </tr>
                <tr class="row2">
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Query to select all items ordered by soldAmount in descending order
                    $sql = "SELECT * FROM items ORDER BY itemStock DESC";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='3'>No rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <td>{$row['itemID']}</td>
                                    <td>{$row['itemName']}</td>
                                    <td>{$row['itemStock']}</td>
                                </tr>\n
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>
        <table>
            <thead>
                <tr class="row1">
                    <th colspan="3">Order By Item Price</th>
                </tr>
                <tr class="row2">
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Query to select all items ordered by soldAmount in descending order
                    $sql = "SELECT * FROM items ORDER BY itemPrice DESC";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='3'>No rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <td>{$row['itemID']}</td>
                                    <td>{$row['itemName']}</td>
                                    <td>{$row['itemPrice']}</td>
                                </tr>\n
                            ";
                        }
                    }
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
