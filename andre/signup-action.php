<?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dct";

    // Connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = $_POST['username'];
    $pnum = $_POST['phone'];
    $email = $_POST['email1'];
    $pword = $_POST['password1'];

    // Query to select all items
    $sql = "INSERT INTO customerform (username, password, phonenumber, emailaddress) values ('$username', '$pnum', '$email', '$pword')";

    if (!$sql) {
        echo "error in signup";
    }
?>