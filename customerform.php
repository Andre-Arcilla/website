<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Form</title>
</head>
<body>
    <h3>Customer Form</h3>
    <hr><form action="connect.php" method="POST">
        <p><b>USERNAME: <br> </b> <input type="text" name="uname" required></p>
        <p><b>PASSWORD: <br></b><input type="password" name="password" required></p>
        <p><b>EMAIL ADDRESS: </b>  <br> <input type="text" name="email" required></p>
        <p><b>PHONE NUMBER: <br> </b> <input type="tel" name="pnumber" required></p>
        <input type="submit" value="Send">

    </form>
    
</body>
</html>