<!DOCTYPE html>
<head>
    <title>admin page</title>
    <style>
        body {
            height: 100%;
            width: 98%;
            background-color: hsl(180, 12%, 45%);
            margin-top: 5%;
            text-align: center;
            font-size: 1.3vw;
        }

        a {
            color: black;
            text-decoration: none !important;
            white-space: nowrap;
        }
    </style>
</head>
    <h1 style="text-align: left; margin-left: 20%;">ADMIN MENU:</h1>

    <h1>
        <a href="viewitems.php">[VIEW ITEMS]</a>
        <a href="edititems.php">[EDIT ITEMS]</a>
    </h1>

    <hr>

    <h1>
        <a href="viewhistory.php">[VIEW CHANGE HISTORY]</a>
        <a href="vieworders.php">[VIEW ORDERS]</a>
    </h1>

    <hr>

    <h1>
        <a href="editadmin.php">[EDIT ADMIN ACCOUNT]</a>
    </h1>

    <hr>

    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dct";

        //connects to the database
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        //checks connection
        if(!$conn) {
            die("connection falied: ".mysqli_connect_error());
        }

        echo "<p>successfully connected to the database</p>";

        mysqli_close($conn);
    ?>
</body>
</html>