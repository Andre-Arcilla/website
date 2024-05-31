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
        <div class="main-website">
            <button class="index-button" onclick="location.href='../index.php';">back to main website</button>
        </div>
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
                    <th>DELETE ITEM</th>
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
                                            <button name='delete_item_btn' value='{$row['itemID']}' onclick='return confirm(\"Are you sure you want to delete this item?\")'>DELETE ITEM</button>
                                        </div>
                                    </td>
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
                <form action="../actions/add-item-action.php" method="post" onsubmit="return validateForm()">
                    <tr>
                        <td></td>
                        <td>
                            <div class='text-box'>
                                <input type='text' name='new_item_id' placeholder='Item ID' id="new_item_id" required>
                            </div>
                        </td>
                        <td>
                            <div class='text-box'>
                                <input type='text' name='new_item_name' placeholder='Item Name' id="new_item_name" required>
                            </div>
                        </td>
                        <td>
                            <div class='text-box'>
                                <input type='text' name='new_item_price' placeholder='Item Price' class='number-only' id="new_item_price" required>
                            </div>
                        </td>
                        <td>
                            <div class='text-box'>
                                <input type='text' name='new_item_stock' placeholder='Item Stock' class='number-only' id="new_item_stock" required>
                            </div>
                        </td>
                        <td>
                            <div class='text-box'>
                                <!-- This input will be disabled for the user to prevent editing -->
                                <input type='text' name='new_item_sold_amount' placeholder='Auto-generated' readonly>
                            </div>
                        </td>
                        <td colspan='2'>
                            <!-- Add button to submit the form for adding the new item -->
                            <div class='text-box'>
                                <input type='submit' value='Add New Item'>
                            </div>
                        </td>
                    </tr>
                </form>
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
                    const inputs = row.querySelectorAll('input[type="text"]:not([name="itemID[]"]), [name^="new_item_"]');
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

            // Add event listeners to cancel buttons
            const deleteButtons = document.querySelectorAll('[name="delete_item_btn"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const itemId = button.value;
                    if (confirm("Are you sure you want to delete this ${itemName}?")) {
                        // Perform delete action
                        window.location.href = `../actions/delete-item-action.php?itemId=${itemId}`;
                    }
                });
            });
        });

        function validateForm() {
            var newItemId = document.getElementById("new_item_id").value;
            var newItemName = document.getElementById("new_item_name").value;

            // Check if itemID has more than 4 characters
            if (newItemId.length > 4) {
                alert("Error: Item ID cannot exceed 4 characters");
                return false; // Prevent form submission
            }

            // Check if itemName has more than 30 characters
            if (newItemName.length > 30) {
                alert("Error: Item Name cannot exceed 30 characters");
                return false; // Prevent form submission
            }

            // Check if itemID and itemName already exist
            var existingItemIds = document.querySelectorAll('input[name="itemID[]"]');
            for (var i = 0; i < existingItemIds.length; i++) {
                if (existingItemIds[i].value === newItemId) {
                    alert("Error: Item ID already exists");
                    return false; // Prevent form submission
                }
            }

            var existingItemNames = document.querySelectorAll('input[name="itemName[]"]');
            for (var j = 0; j < existingItemNames.length; j++) {
                if (existingItemNames[j].value === newItemName) {
                    alert("Error: Item Name already exists");
                    return false; // Prevent form submission
                }
            }

            // If validation passes, allow form submission
            return true;
        }
    </script>
</body>
</html>