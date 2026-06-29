<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$lat = 0;
$lon = 0;
$location = trim($_POST['location'] ?? '');

if (isset($_POST['lat']) && $_POST['lat'] !== '') {
    $lat = (float)$_POST['lat'];
}
if (isset($_POST['lon']) && $_POST['lon'] !== '') {
    $lon = (float)$_POST['lon'];
}

if (preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/', $location, $matches)) {
    $lat = (float)$matches[1];
    $lon = (float)$matches[2];
    $location = '';
}

if ($lat != 0 && $lon != 0) {
    $sql = "SELECT id, shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
            FROM shops
            ORDER BY distance ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ddd", $lat, $lon, $lat);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $location = $location ?: 'Colombo';
    $sql = "SELECT id, shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email FROM shops WHERE district LIKE CONCAT('%', ?, '%')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $location);
    $stmt->execute();
    $result = $stmt->get_result();
}

$shops = [];
while ($row = $result->fetch_assoc()) {
    if (isset($row['distance'])) {
        $row['distance'] = round((float)$row['distance'], 2);
    }
    if (!isset($row['distance']) || (float)$row['distance'] <= 20) {
        $shops[] = $row;
    }
}

if (empty($shops)) {
    echo json_encode(["error" => "No nearby vehicle shops found."]);
} else {
    echo json_encode($shops);
}

$stmt->close();
$conn->close();
?>