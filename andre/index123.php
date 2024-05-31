<?php
    session_start(); //starting a session

    $_SESSION["username"] = "krossing"; //declaring session data
    //unset($_SESSION["username"]); //deleting one session data
    //session_unset(); //deleting all session data

    session_destroy(); //stops the sesssion
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
        echo $_SESSION["username"];
    ?>

    <a href="example.php">next page</a>

</body>
</html>