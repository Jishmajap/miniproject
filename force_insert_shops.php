<?php
$conn = new mysqli('localhost', 'root', '', 'shop_management');
if ($conn->connect_error) {
    die('DB_FAIL: ' . $conn->connect_error);
}

$conn->query("CREATE TABLE IF NOT EXISTS shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    district VARCHAR(255) NOT NULL,
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    owner_email VARCHAR(255) NOT NULL
)");

$shops = [
    ['AutoCare Hub', '12th Main, Koramangala', 'Bangalore', 12.9151, 77.7245, '08023456789', 'autocare@example.com', 'https://autocare.example', 'owner1@example.com'],
    ['Rapid Service Center', '1st Cross, Indiranagar', 'Bangalore', 12.9716, 77.6412, '08022334455', 'rapidservice@example.com', 'https://rapidservice.example', 'owner2@example.com'],
    ['City Wheel Works', 'Brigade Road', 'Bangalore', 12.9720, 77.6060, '08033445566', 'citywheel@example.com', 'https://citywheel.example', 'owner3@example.com'],
    ['Bangalore Tyre Fix', 'HSR Layout', 'Bangalore', 12.9116, 77.6484, '08034567890', 'tyrefix@example.com', 'https://tyrefix.example', 'owner4@example.com'],
    ['Metro Auto Garage', 'Jayanagar', 'Bangalore', 12.9250, 77.5950, '08031234567', 'metroauto@example.com', 'https://metroauto.example', 'owner5@example.com']
];

$stmt = $conn->prepare("INSERT IGNORE INTO shops (shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($shops as $shop) {
    $stmt->bind_param('sssdssss', $shop[0], $shop[1], $shop[2], $shop[3], $shop[4], $shop[5], $shop[6], $shop[7], $shop[8]);
    $stmt->execute();
}
$stmt->close();

$result = $conn->query("SELECT COUNT(*) AS c FROM shops");
$row = $result->fetch_assoc();
echo "inserted_count=" . $row['c'];
$conn->close();
