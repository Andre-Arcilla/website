<!DOCTYPE html>
<head>
    <title>admin page</title>
    <style>
        body {
            display: grid;
            grid-template-areas: 
                "topleft topmid topright"
                "midleft midmid midright"
                "botleft botmid botright";
            grid-template-columns: 1fr 3fr 1fr;
            grid-template-rows: 1fr auto 1fr;
            align-items: center;
            justify-items: center;
            background: url('images/bg2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .box1 {
            grid-area: midmid;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: inline-block;
            margin: 1rem;
            padding: 1rem;
            text-align: center;
            width: 300px;
            min-height: 500px;
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

        $sql = "select * from items";
        $result = mysqli_query($conn, $sql);
    ?>

    <div class="box1">

    </div>

    <?php
        mysqli_close($conn);
    ?>
</body>
</html>