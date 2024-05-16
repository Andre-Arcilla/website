<?php 

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "dct"; 
    $conn = "";

        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
        
        if (!$conn) {
            die ("Luh, Anong ginawa mo???" . mysqli_connect_error());
        } else {
            echo "Connected Successfully!</br>";
        }
        try{
    
            $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    
        }catch(mysqli_sql_exception){
            echo "Error: Connection Lost!";
    
        }
    
        $uname = $_POST['uname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $pnumber = $_POST['pnumber'];
        $uname = mysqli_real_escape_string($conn, $_POST['uname']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pnumber = mysqli_real_escape_string($conn, $_POST['pnumber']);
    
    
        $sql = "INSERT INTO customerform (username, password, emailaddress, phonenumber)
                VALUES ('$uname', '$password','$email','$pnumber')";
    
            if(mysqli_query($conn, $sql)) {
                echo "New Record created successfully";
    
            } else {
                echo "Error . " . $sql. "<br>" . mysqli_error($conn);
            }

    
    
        mysqli_close($conn);
    ?>
