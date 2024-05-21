<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Items</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('images/bg2.jpg') no-repeat center center fixed;
            background-size: cover;
        }

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
            width: 200px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
            margin: 10px;
            overflow: hidden;
            position: relative;
        }

        .container h1 {
            font-size: 1.2em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
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
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php
        $trackingnum = '0928123';
        $name = 'ted';
        $address = 'asd2321';
        $email = 'dqwdq@gmaw.com';
        $pnum = '2222312';
        $orderdate = date("Y-m-d");

        $FMprice = 10.00;
        $FMstock = 32;
        $FMbulkAmount = 5;

        $idprice = 10.00;
        $idstock = 32;
        $idbulkAmount = 5;
    ?>
    <div class="biggercontainer">
        <!-- Face Mask Form -->
        <form action="ITEM SELECTION TEST output.php" method="post" id="facemask-form">
            <input type="hidden" name="item" value="Face Mask">
            <input type="hidden" name="price" value="<?php echo $FMprice; ?>">
            <div class="container">
                <h1>Face Mask</h1>
                <img src="images/temp2.png">
                <p>Price: $<?php echo $FMprice; ?></p>
                <label for="bulk-facemask">
                    <input type="checkbox" id="bulk-facemask" name="bulk" value="N">Buy in Bulk
                </label>
                <div class="number-input">
                    <button type="button" onclick="decrement('facemask')">-</button>
                    <input type="text" value="0" min="0" max="<?php echo $FMstock; ?>" id="quantity-facemask" name="amount">
                    <button type="button" onclick="increment('facemask')">+</button>
                </div>
                <button type="submit">Add to Cart</button>
            </div>
        </form>

        <!-- ID Band Form -->
        <form action="ITEM SELECTION TEST output.php" method="post" id="idband-form">
            <input type="hidden" name="item" value="ID Band">
            <input type="hidden" name="price" value="<?php echo $idprice; ?>">
            <div class="container">
                <h1>ID Band</h1>
                <img src="images/temp2.png">
                <p>Price: $<?php echo $idprice; ?></p>
                <label for="bulk-idband">
                    <input type="checkbox" id="bulk-idband" name="bulk" value="N">Buy in Bulk
                </label>
                <div class="number-input">
                    <button type="button" onclick="decrement('idband')">-</button>
                    <input type="text" value="0" min="0" max="<?php echo $idstock; ?>" id="quantity-idband" name="amount">
                    <button type="button" onclick="increment('idband')">+</button>
                </div>
                <button type="submit">Add to Cart</button>
            </div>
        </form>
    </div>

    <script>
        // Select the checkbox elements
        let facemaskCheckbox = document.getElementById("bulk-facemask");
        let idbandCheckbox = document.getElementById("bulk-idband");

        // Add event listeners to the checkboxes
        facemaskCheckbox.addEventListener("change", function() {
            if (facemaskCheckbox.checked) {
                facemaskCheckbox.value = 'Y';
            } else {
                facemaskCheckbox.value = 'N';
            }
        });

        idbandCheckbox.addEventListener("change", function() {
            if (idbandCheckbox.checked) {
                idbandCheckbox.value = 'Y';
            } else {
                idbandCheckbox.value = 'N';
            }
        });

        // Increment function to increase quantity
        function increment(form) {
            var input = document.getElementById('quantity-' + form);
            var value = parseInt(input.value, 10);
            var max = parseInt(input.max, 10);
            input.value = value < max ? value + 1 : max;
        }

        // Decrement function to decrease quantity
        function decrement(form) {
            var input = document.getElementById('quantity-' + form);
            var value = parseInt(input.value, 10);
            input.value = value > 0 ? value - 1 : 0;
        }

        // Add event listeners and update max quantity based on bulk checkbox for Face Mask
        facemaskCheckbox.addEventListener('change', function() {
            var input = document.getElementById('quantity-facemask');
            var checked = this.checked;
            var max = <?php echo $FMstock; ?>;
            var bulkAmount = <?php echo $FMbulkAmount; ?>;
            input.max = checked ? Math.floor(max / bulkAmount) : max;
            input.value = Math.min(parseInt(input.value, 10), parseInt(input.max, 10));
        });

        // Add event listeners and update max quantity based on bulk checkbox for ID Band
        idbandCheckbox.addEventListener('change', function() {
            var input = document.getElementById('quantity-idband');
            var checked = this.checked;
            var max = <?php echo $idstock; ?>;
            var bulkAmount = <?php echo $idbulkAmount; ?>;
            input.max = checked ? Math.floor(max / bulkAmount) : max;
            input.value = Math.min(parseInt(input.value, 10), parseInt(input.max, 10));
        });

        // Validate input value to ensure it doesn't exceed max for Face Mask
        document.getElementById('quantity-facemask').addEventListener('input', function() {
            var value = parseInt(this.value, 10);
            var max = parseInt(this.max, 10);
            this.value = isNaN(value) ? 0 : value > max ? max : value;
        });

        // Validate input value to ensure it doesn't exceed max for ID Band
        document.getElementById('quantity-idband').addEventListener('input', function() {
            var value = parseInt(this.value, 10);
            var max = parseInt(this.max, 10);
            this.value = isNaN(value) ? 0 : value > max ? max : value;
        });
    </script>
</body>
</html>
