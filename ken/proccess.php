<?php
function get_client_ip()
{
    foreach (array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER)) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return null;
}

$ip = get_client_ip();



$loc = file_get_contents("http://ip-api.com/json/$ip");
$loc_data = json_decode($loc, true); 

if ($loc_data && $loc_data['status'] == 'success') {
    $country = $loc_data['country'];
    $region = $loc_data['regionName'];
    $city = $loc_data['city'];
    $zip = $loc_data['zip'];
    $lat = $loc_data['lat'];
    $lon = $loc_data['lon'];
    $timezone = $loc_data['timezone'];
} else {
    echo "Unable to fetch location data.";
    exit;
}

define("DB_HOST", "localhost");
define("DB_NAME", "ridertracking");
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

    $stmt = $pdo->prepare(
        "INSERT INTO rider_location (ip_address, country, region, city, zip_code, latitude, longitude, timezone) 
        VALUES (:ip_address, :country, :region, :city, :zip_code, :latitude, :longitude, :timezone)"
    );

    $stmt->execute([
        ':ip_address' => $ip,
        ':country' => $country,
        ':region' => $region,
        ':city' => $city,
        ':zip_code' => $zip,
        ':latitude' => $lat,
        ':longitude' => $lon,
        ':timezone' => $timezone
    ]);

    echo "Location data recorded successfully.";

} catch (Exception $ex) {
    echo "An error has occurred: " . $ex->getMessage();
}

echo "<br>$country</br>";
echo "<br>$region</br>";
echo "<br>$city</br>";
echo "<br>$zip</br>";
echo "<br>$lat</br>";
echo "<br>$lon</br>";
echo "<br>$timezone</br>";
echo "<br>" . date("Y-m-d H:i:s") . "</br>";
?>
