<?php   
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        error_reporting(E_ERROR | E_PARSE);
        $db_server = "localhost";
        $db_pass = "root";
        $db_name = "infodatabase";
        $conn = "";

        try {
            $conn = mysqli_connect ($db_server, $db_user, $db_pass, $db_name);

        } catch (mysqli_sql_exception) {
            echo "Error: Connection Lost!";
        }

        $AccountNumber = $_POST['AccountNumber']; 
        $uname = $_POST['uname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $pnumber = $_POST['pnumber'];

        $sql = "UPDATE customerform SET Username = '$Uname', EmailAddress = '$email' WHERE AccountNumber = '$AccountNumber'";

        if (mysqli_query($conn, $sql)) {
            echo "<br> Record Updated";
        } else {
            echo "Error: " . $sql. "<br>" . mysqli_error ($conn);
        }
        mysqli_close($conn);
    }
?>
