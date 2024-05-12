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
        $dbname = "dct";

        //connects to the database
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM order_info INNER JOIN order_items ON order_info.orderID = order_items.orderID ORDER BY trackingNum ASC;";
        $result = mysqli_query($conn, $sql);
    ?>

    <table border="1vw">

        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Customer Email</th>
                <th>Customer Phone Number</th>
                <th>Order Date</th>
                <th>Items</th>
                <th>Bulk (Y/N)</th>
                <th>Amount</th>
                <th>item Total</th>
                <th>Order Total</th>
                <th>Comments</th>
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
                        ";
                    }
                }
                mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <br>

    <a href="adminmain.php">[RETURN]</a>
</body>
</html>