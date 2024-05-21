<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Items</title>
    <style>
    .biggercontainer {
        display: flex;
        gap: 10px;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 200px; /* Set a fixed width for each container */
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 10px;
        margin: 10px; /* Add margin for spacing between containers */
    }

    .container h1 {
        font-size: 1.2em; /* Adjust font size for heading */
        white-space: nowrap; /* Prevent text from wrapping */
        overflow: hidden; /* Hide overflowing text */
        text-overflow: ellipsis; /* Display an ellipsis (...) to indicate overflow */
        max-width: 100%; /* Ensure text doesn't exceed container width */
    }

    .number-input {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }

    .number-input input[type="text"] {
        text-align: center;
        font-size: inherit;
        width: 4rem;
    }

    .number-input button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 4.7px;
        cursor: pointer;
    }

    img {
        width: 100%; /* Ensure the image fills the container */
        height: auto;
        margin-bottom: 10px;
    }
</style>

</head>
<body>
    <?php
        // Connect to the database
        $servername = "localhost";
        $username = "root"; // Replace with your MySQL username
        $password = ""; // Replace with your MySQL password
        $dbname = "dct"; // Replace with your database name
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch items from the database
        $sql = "SELECT * FROM items";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    ?>

    <div class="biggercontainer">
        <?php foreach ($items as $item): ?>
            <form action="ITEM SELECTION TEST output.php" method="post">
            <div class="container">
                <h1><?php echo $item["itemName"]; ?></h1>
                <img src="images/temp2.png"> <!-- Assuming the images are named after the item IDs -->
                <label for="bulk-<?php echo $item["new_itemID"]; ?>">
                    <input type="checkbox" id="bulk-<?php echo $item["new_itemID"]; ?>" name="bulk" value="Y">Buy in Bulk
                </label>
                <div class="number-input">
                    <button type="button" onclick="decrement('quantity-<?php echo $item["new_itemID"]; ?>')">-</button>
                    <input type="text" value="0" min="0" max="<?php echo $item["itemStock"]; ?>" id="quantity-<?php echo $item["new_itemID"]; ?>" name="quantity-<?php echo $item["new_itemID"]; ?>">
                    <button type="button" onclick="increment('quantity-<?php echo $item["new_itemID"]; ?>')">+</button>
                </div>
                <!-- Add hidden inputs to submit the selected item ID and quantity -->
                <input type="hidden" name="selectedItemId" value="<?php echo $item["new_itemID"]; ?>">
                <button type="submit" onclick="updateSelectedItemQuantity(<?php echo $item["new_itemID"]; ?>)">Add to Cart</button>
            </div>
        </form>
        <?php endforeach; ?>
    </div>
    
    <script>
        function increment(id) {
            var input = document.getElementById(id);
            var value = parseInt(input.value, 10);
            var max = parseInt(input.max, 10);
            input.value = value < max ? value + 1 : max;
        }

        function decrement(id) {
            var input = document.getElementById(id);
            var value = parseInt(input.value, 10);
            input.value = value > 0 ? value - 1 : 0;
        }

        function updateMax(id, checkboxId, stock) {
            var input = document.getElementById(id);
            var checkbox = document.getElementById(checkboxId);
            var value = parseInt(input.value, 10);
            var max = checkbox.checked ? Math.floor(stock / <?php echo $item["bulkAmount"]; ?>) : stock;
            input.max = max;
            input.value = Math.min(value, max);
        }

        function addInputListener(id) {
            var input = document.getElementById(id);
            input.addEventListener('input', function() {
                var value = parseInt(input.value, 10);
                var max = parseInt(input.max, 10);
                input.value = isNaN(value) ? 0 : value > max ? max : value;
            });
        }

        <?php foreach ($items as $item): ?>
        var stock<?php echo $item["new_itemID"]; ?> = <?php echo $item["itemStock"]; ?>;
        document.getElementById('bulk-<?php echo $item["new_itemID"]; ?>').addEventListener('change', function() {
            updateMax('quantity-<?php echo $item["new_itemID"]; ?>', 'bulk-<?php echo $item["new_itemID"]; ?>', stock<?php echo $item["new_itemID"]; ?>);
        });
        addInputListener('quantity-<?php echo $item["new_itemID"]; ?>');
        <?php endforeach; ?>
    </script>
</body>
</html>
