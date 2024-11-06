<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$radius = 10; // Radius in kilometers

// SQL query to find shops within the specified radius
$sql = "SELECT shop_name, address, district, latitude, longitude, 
               (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance 
        FROM shops 
        HAVING distance < ? 
        ORDER BY distance 
        LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dddd", $latitude, $longitude, $latitude, $radius);
$stmt->execute();
$stmt->bind_result($shop_name, $address, $district, $lat, $lon, $distance);

$shops = [];
while ($stmt->fetch()) {
    $shops[] = [
        'shop_name' => $shop_name,
        'address' => $address,
        'district' => $district,
        'latitude' => $lat,
        'longitude' => $lon,
        'distance' => $distance
    ];
}

echo json_encode($shops);

$stmt->close();
$conn->close();
?>