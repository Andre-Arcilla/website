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

        th, td {
            padding: .5vw;
            font-size: 2vw;
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

        $sql = "select * from items";
        $result = mysqli_query($conn, $sql);
    ?>

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
                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='6'>no rows returned</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                            <tr>
                                <td>{$row['itemID']}</td>
                                <td>{$row['itemName']}</td>
                                <td>{$row['itemPrice']}</td>
                                <td>{$row['bulkPrice']}</td>
                                <td>{$row['bulkAmount']}</td>
                                <td>{$row['itemStock']}</td>
                            </tr>\n
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