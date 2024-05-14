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

        $Anumber = mysqli_real_escape_string ($conn, $_POST ['AccountNumber']);
        $uname = mysqli_real_escape_string ($conn, $_POST['uname']);
        $password = mysqli_real_escape_string ($conn, $_POST['password']);
        $email = mysqli_real_escape_string ($conn, $_POST['email']);

        $sql = "UPDATE customerform SET Username = '$Uname', Password = '$password', EmailAddress = '$email' WHERE AccountNumber = '$Anumber'";
        $exists = "SELECT * FROM customerform WHERE AccountNumber = '$Anumber'";

            if (mysqli_query($conn, $sql)) {
                echo "<br> Record Updated";
            } else {
                echo "Error: " . $sql. "<br>" . mysqli_error ($conn);
            }
            mysqli_close($conn);
    }
?>