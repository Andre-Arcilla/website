<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminindex.css">
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
        <button class="sidebar-button" onclick="location.href='adminEditItems.php';">edit items</button>
        <button class="sidebar-button" onclick="location.href='adminViewOrders.php';">view orders</button>
    </div>
</body>
</html>
