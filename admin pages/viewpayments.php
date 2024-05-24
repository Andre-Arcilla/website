<?php
    session_start();

    // Check if the usertype is not set or is not 'admin'
    if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] != 'admin') {
        // Redirect to the login page
        header("Location: ../login.php");
        exit(); // Stop further execution
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
            margin-bottom: 4rem;
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

        $sql = "select * from payments";
        $result = mysqli_query($conn, $sql);
    ?>

    <!-- Return link -->
    <a href="adminmain.php">[RETURN]</a>

    <table>

        <thead>
            <tr>
                <th>Order ID</th>
                <th>GCash Name</th>
                <th>GCash Number</th>
                <th>GCash Reference Number</th>
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
                                <td>{$row['orderID']}</td>
                                <td>{$row['gcashName']}</td>
                                <td>{$row['gcashNumber']}</td>
                                <td>{$row['gcashReferenceNum']}</td>
                            </tr>\n
                        ";
                    }
                }
                mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>
</html>