<?php
$conn = new mysqli('localhost', 'root', '', 'shop_management');
if ($conn->connect_error) {
    file_put_contents(__DIR__ . '/debug_shop_data.log', 'DB_CONNECT_FAIL: ' . $conn->connect_error . PHP_EOL);
    exit(1);
}

$log = [];
$log[] = 'DB_OK';

$result = $conn->query("SHOW TABLES LIKE 'shops'");
if ($result->num_rows === 0) {
    $conn->query("CREATE TABLE shops (id INT AUTO_INCREMENT PRIMARY KEY, shop_name VARCHAR(255) NOT NULL, address TEXT NOT NULL, district VARCHAR(255) NOT NULL, latitude DECIMAL(10,8) NOT NULL, longitude DECIMAL(11,8) NOT NULL, phone_number VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, owner_email VARCHAR(255) NOT NULL)");
    $log[] = 'TABLE_CREATED';
}

$shops = [
    ['AutoCare Hub', '12th Main, Koramangala', 'Bangalore', 12.91510000, 77.72450000, '08023456789', 'autocare@example.com', 'https://autocare.example', 'owner1@example.com'],
    ['Rapid Service Center', '1st Cross, Indiranagar', 'Bangalore', 12.97160000, 77.64120000, '08022334455', 'rapidservice@example.com', 'https://rapidservice.example', 'owner2@example.com'],
    ['City Wheel Works', 'Brigade Road', 'Bangalore', 12.97200000, 77.60600000, '08033445566', 'citywheel@example.com', 'https://citywheel.example', 'owner3@example.com'],
    ['Bangalore Tyre Fix', 'HSR Layout', 'Bangalore', 12.91160000, 77.64840000, '08034567890', 'tyrefix@example.com', 'https://tyrefix.example', 'owner4@example.com'],
    ['Metro Auto Garage', 'Jayanagar', 'Bangalore', 12.92500000, 77.59500000, '08031234567', 'metroauto@example.com', 'https://metroauto.example', 'owner5@example.com'],
    ['Quick Repair Point', 'Whitefield', 'Bangalore', 12.96980000, 77.75000000, '08039876543', 'quickrepair@example.com', 'https://quickrepair.example', 'owner6@example.com']
];

$stmt = $conn->prepare("INSERT INTO shops (shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($shops as $shop) {
    $check = $conn->prepare("SELECT id FROM shops WHERE shop_name = ? AND address = ?");
    $check->bind_param('ss', $shop[0], $shop[1]);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        $stmt->bind_param('sssdssss', $shop[0], $shop[1], $shop[2], $shop[3], $shop[4], $shop[5], $shop[6], $shop[7], $shop[8]);
        $stmt->execute();
    }
    $check->close();
}
$stmt->close();

$countRes = $conn->query("SELECT COUNT(*) AS c FROM shops WHERE district='Bangalore'");
$countRow = $countRes->fetch_assoc();
$log[] = 'BANGALORE_COUNT=' . $countRow['c'];

$lat = 12.9141;
$lon = 77.7239;
$query = "SELECT shop_name, address, district, latitude, longitude, (6371 * ACOS(COS(RADIANS($lat)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($lon)) + SIN(RADIANS($lat)) * SIN(RADIANS(latitude)))) AS distance FROM shops HAVING distance <= 20 ORDER BY distance ASC";
$nearRes = $conn->query($query);
$rows = [];
while ($row = $nearRes->fetch_assoc()) {
    $rows[] = $row;
}
$log[] = 'NEARBY_RESULTS=' . count($rows);
$log[] = json_encode($rows, JSON_UNESCAPED_SLASHES);

file_put_contents(__DIR__ . '/debug_shop_data.log', implode(PHP_EOL, $log) . PHP_EOL);
$conn->close();
