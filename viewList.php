<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
</head>
<body>
    <?php 
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "dct"; 
        $conn = "";
    
    
        try{
    
            $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    
        }catch(mysqli_sql_exception){
            echo "Error: Connection Lost!";
    
        }

        $sql = "SELECT * FROM customerform";
        $result = mysqli_query($conn, $sql);

    
    ?>

    <table border="2">
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                

            </tr>

        </thead>
    <?php
        if(mysqli_num_rows($result) == 0) {
            echo '<tr> <td colspan="6">No Rows Returned</td></tr>';

        } else {
            while($row = mysqli_fetch_assoc($result)) {
                echo "
                        <tr>
                            <td>{$row["accountnumber"]}</td>
                            <td>{$row["username"]}</td>
                            <td>{$row["password"]}</td>
                            <td>{$row["emailaddress"]}</td>
                            <td>{$row["phonenumber"]}</td>
                            <br>
                        </tr>
                    ";

            }

        }
    
    
    ?>

    </table>
    
</body>
</html>