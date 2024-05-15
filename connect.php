<?php 

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

    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pnumber = mysqli_real_escape_string($conn, $_POST['pnumber']);


    $sql = "INSERT INTO customerform (Username, password, EmailAddress, PhoneNumber)
            VALUES ('$uname', '$email', '$pnumber', '$password')";

    try {
        if(mysqli_query($conn, $sql)) {
            echo "New Record created successfully";

        } else {
            echo "Error . " . $sql. "<br>" . mysqli_error($conn);

        }


    }catch(mysqli_sql_exception $e) {
        echo "ERROR: MALI ANG CODE";

    }


    mysqli_close($conn);
?>
