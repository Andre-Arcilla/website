<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <form action="..\indexa.html" id="signup-form" class="signup-form">

    <?php
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dct";

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
        $type = 'user'; // Assuming the type is always 'user' for sign-ups

        // Validate and sanitize inputs
        $username = htmlspecialchars(strip_tags($username));
        $phone = htmlspecialchars(strip_tags($phone));
        $email = htmlspecialchars(strip_tags($email));

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $sql = "SELECT accountnumber FROM customerform WHERE emailaddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo 'Email already exists. Please use a different email address.
            <button type="button" onclick="window.location.href=\'signup.php\'">Return to Sign-Up Page</button>';
        } else {
            // Use prepared statements to prevent SQL injection
            $stmt->close(); // Close the previous statement

            $stmt = $conn->prepare("INSERT INTO customerform (username, password, phonenumber, emailaddress, type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $hashed_password, $phone, $email, $type);

            if ($stmt->execute()) {
                echo "Sign up successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    ?>

            <button type="submit">return to home page</button>
        </form>
    </div>
</body>
</html>
