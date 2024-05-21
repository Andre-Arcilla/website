<?php
define("DB_HOST", "localhost");
define("DB_NAME", "gps_tracking");
define("DB_USER", "root");
define("DB_PASSWORD", "");

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Check if the required POST parameters are set
    if (isset($_POST['rider_id']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $rider_id = $_POST['rider_id'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO location_data (rider_id, latitude, longitude) 
            VALUES (:rider_id, :latitude, :longitude)
        ");

        $stmt->execute([
            ':rider_id' => $rider_id,
            ':latitude' => $latitude,
            ':longitude' => $longitude
        ]);

        echo "Location data recorded successfully.";
    } else {
        echo "Required parameters are missing.";
    }

} catch (Exception $ex) {
    echo "An error occurred: " . $ex->getMessage();
}
?>
