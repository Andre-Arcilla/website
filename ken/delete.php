<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['submit'])) {
            $db_server = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_name = "infodatabase"; 
            $conn = "";


            try{

                $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

            }catch(mysqli_sql_exception){
                echo "Error: Connection Lost!";

            }

            $AccountNumber = mysqli_real_escape_string($conn, $_POST['AccountNumber']);

            $sql = "SELECT * FROM customerform WHERE AccountNumber='$AccountNumber'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM customerform WHERE AccountNumber='$AccountNumber'";
                if(mysqli_query($conn, $sql)) {
                    echo"<br> Record Deleted";

                } else {
                    echo "Error Deleted record." . mysqli_errno($conn);

                }


            }else {
                echo "No Record Exist";

            }
            mysqli_close($conn);

        }

    }

?>