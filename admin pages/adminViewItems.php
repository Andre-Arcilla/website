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

    //connects to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $sql = "select * from items";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminViewItems.css">
</head>
<body>
    <header>
        <img src="..\images\DCT no bg v3.png">
        <div class="main-website"><a href="#">back to main website</a></div>
    </header>
    <div class="sidebar">
        <button class="sidebar-button" id="hidden">aaaa</button>
        <button class="sidebar-button" onclick="location.href='adminStatistics.php';">statistics</button>
        <button class="sidebar-button" id="selected">view items</button>
        <button class="sidebar-button" onclick="location.href='adminEditItems.php';">edit items</button>
        <button class="sidebar-button" onclick="location.href='adminViewOrders.php';">view orders</button>
    </div>
    <div class="main-content">
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Amount Sold</th>
                    <th>Item Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='6'>no rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <td>{$row['itemID']}</td>
                                    <td>{$row['itemName']}</td>
                                    <td>{$row['itemPrice']}</td>
                                    <td>{$row['soldAmount']}</td>
                                    <td>{$row['itemStock']}</td>
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
