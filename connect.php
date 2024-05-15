<?php 

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "infodatabase"; 
    $conn = "";


    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    
    if (!$conn) {
        die ("Luh, Anong ginawa mo???" . mysqli_connect_error());
    } else {
        echo "Connected Successfully!</br>";
    }

    $uname = $_POST['uname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $pnumber = $_POST['pnumber'];


    $sql = "INSERT INTO customerform (Username, password, EmailAddress, PhoneNumber)
            VALUES ('$uname', '$email', '$pnumber', '$password')";


    if(mysqli_query($conn, $sql)) {
        echo "New Record created successfully";

    } else {
        echo "Error . " . $sql. "<br>" . mysqli_error($conn);

    }
    mysqli_close($conn);
?>
