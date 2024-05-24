<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../signup-login-styles.css">
</head>
<body>
    <div class="container">
        <div class="signup-form">
            <?php
                // Start a session at the beginning
                session_start();

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

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get POST data and sanitize it
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    // Validate and sanitize inputs
                    $email = htmlspecialchars(strip_tags($email));

                    // Check if email exists
                    $sql = "SELECT accountID, password, type FROM accounts WHERE emailaddress = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        // Email found, now fetch the hashed password
                        $stmt->bind_result($accountID, $hashed_password_from_db, $type);
                        $stmt->fetch();

                        // Verify the hashed password
                        if (password_verify($password, $hashed_password_from_db)) {
                            // Set session variables
                            $_SESSION["usertype"] = $type;
                            $_SESSION["userid"] = $accountID;
                            $_SESSION["email"] = $email;

                            header("Location: ../indexa.php");
                            exit();
                        } else {
                            echo '<span>Incorrect password.</span><br>
                            <button type="button" onclick="window.location.href=\'/..login.php\'">Return to Login Page</button>';
                        }
                    } else {
                        echo '<span>Email not found.</span><br>
                        <button type="button" onclick="window.location.href=\'../login.php\'">Return to Login Page</button>';
                    }

                    // Close the statement and connection
                    $stmt->close();
                }
                
                $conn->close();
            ?>
            <button type="button" onclick="window.location.href='../indexa.php'">Return to Home Page</button>
        </div>
    </div>
</body>
</html>
