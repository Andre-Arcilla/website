<?php
define("DB_HOST", "localhost");
define("DB_NAME", "riderTracking");
define("DB_USER", "root");
define("DB_PASSWORD", "");

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", 
        DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $stmt = $pdo->query("SELECT * FROM rider_location ORDER BY recorded_at DESC");
    $locations = $stmt->fetchAll();

} catch (Exception $ex) {
    echo "An error occurred: " . $ex->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Location Summary</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Rider Location Summary</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>IP Address</th>
                <th>Country</th>
                <th>Region</th>
                <th>City</th>
                <th>Zip Code</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Timezone</th>
                <th>Recorded At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $location): ?>
                <tr>
                    <td><?php echo htmlspecialchars($location['id']); ?></td>
                    <td><?php echo htmlspecialchars($location['ip_address']); ?></td>
                    <td><?php echo htmlspecialchars($location['country']); ?></td>
                    <td><?php echo htmlspecialchars($location['region']); ?></td>
                    <td><?php echo htmlspecialchars($location['city']); ?></td>
                    <td><?php echo htmlspecialchars($location['zip_code']); ?></td>
                    <td><?php echo htmlspecialchars($location['latitude']); ?></td>
                    <td><?php echo htmlspecialchars($location['longitude']); ?></td>
                    <td><?php echo htmlspecialchars($location['timezone']); ?></td>
                    <td><?php echo htmlspecialchars($location['recorded_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
