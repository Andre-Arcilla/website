<?php
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

    // Remove item from cart if remove button is clicked
    if (isset($_POST["remove_item"]) && isset($_POST["itemID"])) {
        $itemIDToRemove = $_POST["itemID"];
        if (isset($_SESSION["cart"][$itemIDToRemove])) {
            unset($_SESSION["cart"][$itemIDToRemove]);
        }
    }
    
    $_SESSION['pwd-token'] = 0;
    $_SESSION['sc-token'] = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="orderInfo.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img id="header-logo" class="header-logo" src="images/DCT no bg v2.png" alt="Logo">
                <nav class="navigation">
                    <button class="sidebar-button" onclick="location.href='index.php';">Home</button>
                    <button class="sidebar-button" onclick="location.href='products.php';">Store</button>
                    <button class="sidebar-button" onclick="location.href='orders.php';">Your Orders</button>
                </nav>
            </div>

            <?php if (isset($_SESSION["userid"])): ?>
                <div>
                    <b>Please enter your information, <?php echo $_SESSION["name"]; ?>.</b>
                </div>
            <?php endif; ?>

            <nav class="account-info">
                <?php if (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == 'admin'): ?>
                    <button class="sidebar-button" onclick="location.href='admin pages/adminIndex.php';">Admin Page</button>
                <?php endif; ?>
                <?php if (isset($_SESSION["userid"])): ?>
                    <button class="sidebar-button" onclick="location.href='actions/logout-action.php';">Logout</button>
                <?php else: ?>
                    <button class="sidebar-button" onclick="location.href='login.php';">Login</button>
                    <button class="sidebar-button" onclick="location.href='signup.php';">Signup</button>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="contents">
        <form action="actions/checkout-action.php" method="post" onsubmit="return validateForm()">
            <div class="title">
                <button onclick="location.href='orderTotal.php';">Previous Step</button>
                <span>ORDER INFORMATION</span>
                <button type="submit" name="submit">Next Step</button>
            </div>

            <div class="confirm-box">
                <div class="form-box">
                    <div class="address-box">
                        <h1>DELIVERY ADDRESS</h1>
                        <div class="input-box">
                            <label>STREET ADDRESS: </label>
                            <input type="text" name="street" placeholder="456 Sunset Boulevard" required>
                        </div>

                        <div class="input-box">
                            <label>BARANGAY: </label>
                            <input type="text" name="barangay" placeholder="Barangay Sunshine" required>

                            
                            <label>CITY: </label>
                            <input type="text" name="city" placeholder="Manila" required>
                        </div>

                        <div class="input-box">
                            <label>PROVINCE: </label>
                            <input type="text" name="province" placeholder="Metro Manila" required>

                            <label>POSTAL CODE: </label>
                            <input type="text" name="postal" placeholder="1008" required>
                        </div>
                    </div>

                    <br>
                    <hr>
                    <br>

                    <?php

                    if ($_SESSION['pwd-checkbox'] == 'checked' || $_SESSION['sc-checkbox'] == 'checked') {
                    echo '<div class="discount-box">
                        <h1>DISCOUNT INFORMATION</h1>
                        <div class="pwd-cs-box">';

                        if ($_SESSION['pwd-checkbox'] == 'checked') {
                            echo '<div class="pwd-cs-items">
                                <label>
                                    PWD ID NUMBER 
                                </label>
                                <input type="text" name="pwdID" id="pwd-input" placeholder="A12346199" required>
                            </div>';}

                        if ($_SESSION['sc-checkbox'] == 'checked') {
                            echo '<div class="pwd-cs-items">
                                <label>
                                    SENIOR CITIZEN ID NUMBER 
                                </label>
                                <input type="text" name="scID" id="sc-input" placeholder="A12346199" required>
                            </div>';}
                        echo '</div>
                    </div>

                    <br>
                    <hr>
                    <br>';}
                    ?>

                    <h1>PAYMENT INFORMATION</h1>
                    <div class="qr-box">
                        <div class="qr-code">
                            <img src="images\qrcode.png" class="qr">
                            <h1>SCAN THIS QR CODE</h1>
                        </div>
                        <div class="payment-info">
                            <label>
                                <h2>GCash Account Name:</h2>
                                <input type="text" name="gcashName" required>
                            </label>
                            <label>
                                <h2>GCash Account Number:</h2>
                                <input type="text" name="gcashNumber" required>
                            </label>
                            <label>
                                <h2>Reference Number:</h2>
                                <input type="text" name="gcashReferenceNum" required>
                            </label>
                        </div>
                    </div>

                    <br>
                    <hr>
                    <br>

                    <div class="consent-box">
                        <input type="checkbox" name="refund_agreement" id="refund_agreement">
                        <label for="refund_agreement">
                            <b>I understand that Delta Chemical Trading is not obligated to issue a refund for a canceled order.</b>
                        </label>
                        <br>
                        <br>
                        <button type="submit" name="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-nav">
                Contact Number: <a>0976-525-4721</a>
                Email Address: <a>delta.trading@gmail.com</a>
            </div>
            <img class="easter-egg" src="images\arisbm.gif">
        </div>
    </footer>

    <script>
        function validateForm() {
            var refund_agreement = document.getElementById("refund_agreement").checked;
            if (!refund_agreement) {
                alert("Please agree to the refund policy.");
                return false; // Prevent form submission
            }
            
            // Additional validations or form submission logic can be added here
            
            return true; // Allow form submission
        }
    </script>
</body>
</html>

<?php
    $conn->close();
?>