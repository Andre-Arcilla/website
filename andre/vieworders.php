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
        }

        table {
            width: 60vw;
        }

        th, td, #customer {
            padding: .5vw;
            font-size: 1.5vw;
        }

        a {
            color: black;
            text-decoration: none !important;
            font-size: 1.5vw;
        }
    </style>
</head>
<body>
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "delta";

        //connects to the database
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM order_info INNER JOIN order_items ON order_info.orderID = order_items.orderID ORDER BY trackingNum ASC;";
        $result = mysqli_query($conn, $sql);
    ?>

    <table border="1vw">
        <?php
            $string2 = "";


            if (mysqli_num_rows($result) == 0) {
                echo "<tr><td colspan='6'><img src='images/arisbm.gif'> no orders yet! <img src='images/arisbm.gif'></td></tr>";
            } else {
                while ($row = mysqli_fetch_assoc($result)) {

                    $string1 = $row['trackingNum'];
                    if ($string1 != $string2) {
                        echo "
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Tracking Number</th>
                                <th>Customer Name</th>
                                <th>Customer Address</th>
                                <th>Customer Email</th>
                                <th>Customer Phone Number</th>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                <th>Comments</th>
                            </tr>
                            <tr>
                                <td>$string1</td>
                                <td>{$row['customerName']}</td>
                                <td>{$row['orderAddress']}</td>
                                <td>{$row['orderEmail']}</td>
                                <td>{$row['orderPNum']}</td>
                                <td>{$row['orderDate']}</td>
                                <td>{$row['orderTotal']}</td>
                                <td>{$row['orderComment']}</td>
                            </tr>
                            <tr>
                                <th colspan='2'>Items</th>
                                <th colspan='2'>Bulk (Y/N)</th>
                                <th colspan='2'>Amount</th>
                                <th colspan='2'>item Total</th>
                            </tr>
                            <tr>
                                <td colspan='2'>{$row['itemName']}</td>
                                <td colspan='2'>{$row['bulk']}</td>
                                <td colspan='2'>{$row['itemAmount']}</td>
                                <td colspan='2'>{$row['totalPrice']}</td>
                            </tr>
                        ";
                    } else if ($string1 == $string2) {
                        echo "
                            <tr>
                                <td>{$row['itemName']}</td>
                                <td>{$row['bulk']}</td>
                                <td>{$row['itemAmount']}</td>
                                <td>{$row['totalPrice']}</td>
                                <td>{$row['orderTotal']}</td>
                                <td>{$row['orderComment']}</td>
                            </tr>
                        ";
                    }
                    $string2 = $string1;

                    /*echo "
                        <tr>
                            <td><a id='customer' href='customerpage.php'>[{$row['trackingNum']}]</a></td>
                            <td>{$row['customerName']}</td>
                            <td>{$row['orderAddress']}</td>
                            <td>{$row['orderEmail']}</td>
                            <td>{$row['orderPNum']}</td>
                            <td>{$row['orderDate']}</td>
                            <td>{$row['itemID']}</td>
                            <td>{$row['bulk']}</td>
                            <td>{$row['itemAmount']}</td>
                            <td>{$row['totalPrice']}</td>
                            <td>{$row['orderTotal']}</td>
                            <td>{$row['orderComment']}</td>
                        </tr>
                    ";*/
                }
            }
            mysqli_close($conn);
        ?>
    </table>

    <br>

    <a href="adminmain.php">[RETURN]</a>
</body>
</html>