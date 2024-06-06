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
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all items
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
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
        <img src="../images/DCT no bg v3.png" alt="DCT Logo">
        <div class="main-website">
            <button class="index-button" onclick="location.href='../index.php';">Back to Main Website</button>
        </div>
    </header>
    <div class="sidebar">
        <button class="sidebar-button" id="hidden">aaaa</button>
        <button class="sidebar-button" onclick="location.href='adminStatistics.php';">Statistics</button>
        <button class="sidebar-button" onclick="location.href='adminViewItems.php';">View Items</button>
        <button class="sidebar-button" id="selected">Edit Items</button>
        <button class="sidebar-button" onclick="location.href='adminViewOrders.php';">View Orders</button>
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
                if ($result->num_rows == 0) {
                    echo "<tr><td colspan='8'>No items found</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<form method='post' action='../actions/edititems-action.php'>
                            <tr>
                                <td>
                                    <div class='text-box'>
                                        <button name='delete_item_btn' value='{$row['itemID']}' data-itemname='{$row['itemName']}'>DELETE ITEM</button>
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

                $conn->close();
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
                                <input type='text' name='new_item_sold_amount' placeholder='Auto-generated' readonly>
                            </div>
                        </td>
                        <td colspan='2'>
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
            applyRowStyles();
            addEventListenersToButtons();
        });

        function applyRowStyles() {
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.classList.toggle('even', index % 2 === 0);
                    row.classList.toggle('odd', index % 2 !== 0);
                });
            });
        }

        function addEventListenersToButtons() {
            document.querySelectorAll('.update-button').forEach(button => {
                button.addEventListener('click', toggleEdit);
            });

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', makeReadOnly);
            });

            document.querySelectorAll('.number-only').forEach(input => {
                input.addEventListener('input', restrictToNumbers);
            });

            document.querySelectorAll('[name="delete_item_btn"]').forEach(button => {
                button.addEventListener('click', confirmDelete);
            });
        }

        function toggleEdit(event) {
            const row = event.target.closest('tr');
            const inputs = row.querySelectorAll('input[type="text"]:not([name="itemID[]"]), [name^="new_item_"]');
            const readOnlyState = inputs[0].readOnly;
            inputs.forEach(input => {
                input.readOnly = !readOnlyState;
                input.style.backgroundColor = readOnlyState ? "white" : "transparent";
            });
            event.target.value = readOnlyState ? "Cancel" : "Update";
        }

        function makeReadOnly(event) {
            const inputs = event.target.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.readOnly = true;
                input.style.backgroundColor = "transparent";
            });
        }

        function restrictToNumbers(event) {
            event.target.value = event.target.value.replace(/[^0-9]/g, '');
        }

        function confirmDelete(event) {
            event.preventDefault();
            const button = event.target;
            const itemId = button.value;
            const itemName = button.getAttribute('data-itemname');
            if (confirm(`Are you sure you want to delete ${itemName}?`)) {
                window.location.href = `../actions/delete-item-action.php?itemId=${itemId}`;
            }
        }

        document.querySelectorAll('[name="delete_item_btn"]').forEach(button => {
            button.addEventListener('click', confirmDelete);
        });

        function validateForm() {
            const newItemId = document.getElementById("new_item_id").value;
            const newItemName = document.getElementById("new_item_name").value;

            if (newItemId.length > 4) {
                alert("Error: Item ID cannot exceed 4 characters");
                return false;
            }

            if (newItemName.length > 30) {
                alert("Error: Item Name cannot exceed 30 characters");
                return false;
            }

            const existingItemIds = document.querySelectorAll('input[name="itemID[]"]');
            for (let i = 0; i < existingItemIds.length; i++) {
                if (existingItemIds[i].value === newItemId) {
                    alert("Error: Item ID already exists");
                    return false;
                }
            }

            const existingItemNames = document.querySelectorAll('input[name="itemName[]"]');
            for (let j = 0; j < existingItemNames.length; j++) {
                if (existingItemNames[j].value === newItemName) {
                    alert("Error: Item Name already exists");
                    return false;
                }
            }

            return true;
        }
    </script>
</body>
</html>
