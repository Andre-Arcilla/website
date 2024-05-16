<?php

        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "infodatabase"; 
        $conn = "";


        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);


        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


            $AccountNumber = $_POST['AccountNumber'];
            $uname =  $_POST['username'];
            $password = $_POST['passWord'];
            $phonenumber = $_POST['phoneNumber'];
            $email = $_POST['emailAddress'];

                $sql = "UPDATE customerform 
                        SET username = '$uname', 
                            passWord = '$password', 
                            phoneNumber = '$phonenumber', 
                            emailAddress = '$email' 
                        WHERE AccountNumber = '$AccountNumber'";


            if ($conn->query($sql) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            $conn->close();
?>
