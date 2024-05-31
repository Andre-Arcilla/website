<?php
    session_start();

    // Check if the usertype is not set or is not 'admin'
    if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] != 'admin') {
        // Redirect to the login page
        header("Location: ../login.php");
        exit(); // Stop further execution
    }

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "delta";

    // Connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select all items
    $sql = "SELECT * FROM items";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminEditItems.css">
</head>
<body>
    <header>
        <img src="..\images\DCT no bg v3.png">
        <div class="main-website"><a href="#">back to main website</a></div>
    </header>
    <div class="sidebar">
        <button class="sidebar-button" id="hidden">aaaa</button>
        <button class="sidebar-button" onclick="location.href='adminStatistics.php';">statistics</button>
        <button class="sidebar-button" onclick="location.href='adminViewItems.php';">view items</button>
        <button class="sidebar-button" id="selected">edit items</button>
        <button class="sidebar-button" onclick="location.href='adminViewOrders.php';">view orders</button>
    </div>
    <div class="main-content">
        <table border="1vw">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Stock</th>
                    <th>Amount Sold</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Display items in table rows
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='6'>No rows returned</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<form method='post' action='edititems-action.php'>
                                <tr>
                                    <td>
                                        <div class='text-box'>
                                            <input type='text' name='itemID[]' value='{$row['itemID']}' readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='text' name='itemName[]' value='{$row['itemName']}' readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='text' name='itemPrice[]' value='{$row['itemPrice']}' readonly class='number-only'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='text' name='itemStock[]' value='{$row['itemStock']}' readonly class='number-only'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='text' name='soldAmount[]' value='{$row['soldAmount']}' readonly class='number-only'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='button' value='Update' class='update-button'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='text-box'>
                                            <input type='submit' value='Submit'>
                                        </div>
                                    </td>
                                </tr>
                            </form>";
                        }
                    }

                    // Close database connection
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all table elements
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                // Get all rows in the table
                const rows = table.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    // Apply classes based on row index
                    if (index % 2 === 0) {
                        row.classList.add('even');
                    } else {
                        row.classList.add('odd');
                    }
                });
            });

            // Add event listeners to update buttons
            const updateButtons = document.querySelectorAll('.update-button');
            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = button.closest('tr');
                    const inputs = row.querySelectorAll('input[type="text"]:not([name="itemID[]"])');
                    let readOnlyState = inputs[0].readOnly;
                    inputs.forEach(input => {
                        input.readOnly = !input.readOnly;
                        input.style.backgroundColor = input.readOnly ? "transparent" : "white";
                    });
                    button.value = readOnlyState ? "Cancel" : "Update";
                });
            });

            // Add event listeners to forms to revert to read-only on submit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const inputs = form.querySelectorAll('input[type="text"]');
                    inputs.forEach(input => {
                        input.readOnly = true;
                        input.style.backgroundColor = "transparent";
                    });
                });
            });

            // Prevent letters from being inputted in number-only fields
            const numberInputs = document.querySelectorAll('.number-only');
            numberInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });
        });
    </script>
</body>
</html>
