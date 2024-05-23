<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../signup-login-styles.css">
</head>
<body>
    <div class="container">
        <div class="signup-form">
            <?php
                // Database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "delta";

                // Connect to the database
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get POST data and sanitize it
                $username = $_POST['username'];
                $phone = $_POST['phone'];
                $email = $_POST['email1'];
                $password = $_POST['password1'];
                $address = $_POST['address'];

                // Validate and sanitize inputs
                $username = htmlspecialchars(strip_tags($username));
                $phone = htmlspecialchars(strip_tags($phone));
                $email = htmlspecialchars(strip_tags($email));
                $address = htmlspecialchars(strip_tags($address));

                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Check if email already exists
                $sql = "SELECT emailaddress FROM accounts WHERE emailaddress = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                // Check if the 'type' key exists in $_POST array
                if (isset($_POST['type'])) {
                    $type = $_POST['type'];
                } else {
                    $type = 'user';
                }

                    //Tells user if email is already in-use
                if ($stmt->num_rows > 0) {
                    echo '<span>Email already exists. Please use a different email address.</span><br>
                    <button type="button" onclick="window.location.href=\'signup.php\'">Return to Sign-Up Page</button>';
                } else {
                    // Use prepared statements to prevent SQL injection
                    $stmt->close(); // Close the previous statement

                    $stmt = $conn->prepare("INSERT INTO accounts (name, password, phonenumber, emailaddress, address, type) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $hashed_password, $phone, $email, $address, $type);

                    if ($stmt->execute()) {
                        header("Location: ../indexa.php");
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                }

                // Close the statement and connection
                $stmt->close();
                $conn->close();
            ?>
            <button type="button" onclick="window.location.href='../indexa.php'">Return to Home Page</button>
        </div>
    </div>
</body>
</html>
