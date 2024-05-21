<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Face Masks</title>
    <style>
        .biggercontainer  {
            display: flex;
            gap: 10px;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .container {
            display: flex;
            background-color: #f2f2f2;
            padding: 20px;
            width: fit-content;
            border-radius: 10px;
            flex-direction: column;
            align-items: center;
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
            width: 10vw;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php
        $stock = 32; // Assume you fetched the stock value from the database
        $bulkAmount = 5; // Bulk amount specified in PHP
    ?>
    <div class="biggercontainer">
        <form action="ITEM SELECTION TEST output.php" method="post">
            <div class="container">
                <h1>Face Mask Cart</h1>
                <img src="images/temp2.png">
                <label for="bulk-mask"><input type="checkbox" id="bulk-mask" name="bulk" value="Y">Buy in Bulk</label>
                <div class="number-input">
                    <button type="button" onclick="decrement('quantity-mask')">-</button>
                    <input type="text" value="0" min="0" max="<?php echo $stock; ?>" id="quantity-mask" name="amount">
                    <button type="button" onclick="increment('quantity-mask')">+</button>
                </div>
                <button type="submit">Add to Cart</button>
            </div>
        </form>
        
        <form action="ITEM SELECTION TEST output.php" method="post">
            <div class="container">
                <h1>ID Band</h1>
                <img src="images/temp2.png">
                <label for="bulk-band"><input type="checkbox" id="bulk-band" name="bulk" value="Y">Buy in Bulk</label>
                <div class="number-input">
                    <button type="button" onclick="decrement('quantity-band')">-</button>
                    <input type="text" value="0" min="0" max="<?php echo $stock; ?>" id="quantity-band" name="amount">
                    <button type="button" onclick="increment('quantity-band')">+</button>
                </div>
                <button type="submit">Add to Cart</button>
            </div>
        </form>
    </div>
    
    <script>
        var stock = <?php echo $stock; ?>;
        var bulkAmount = <?php echo $bulkAmount; ?>;

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

        function updateMax(id, checkboxId) {
            var input = document.getElementById(id);
            var checkbox = document.getElementById(checkboxId);
            var value = parseInt(input.value, 10);
            var max = checkbox.checked ? Math.floor(stock / bulkAmount) : stock;
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

        document.getElementById('bulk-mask').addEventListener('change', function() {
            updateMax('quantity-mask', 'bulk-mask');
        });

        document.getElementById('bulk-band').addEventListener('change', function() {
            updateMax('quantity-band', 'bulk-band');
        });

        addInputListener('quantity-mask');
        addInputListener('quantity-band');
    </script>
</body>
</html>