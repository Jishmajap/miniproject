<?php
$conn = new mysqli("localhost", "root", "", "shop_management");
if ($conn->connect_error) {
    file_put_contents(__DIR__ . '/bangalore_shops_import.log', "DB error: " . $conn->connect_error . PHP_EOL);
    exit(1);
}

$shops = [
    ["AutoCare Hub", "12th Main, Koramangala", "Bangalore", 12.91510000, 77.72450000, "08023456789", "autocare@example.com", "https://autocare.example", "owner1@example.com"],
    ["Rapid Service Center", "1st Cross, Indiranagar", "Bangalore", 12.97160000, 77.64120000, "08022334455", "rapidservice@example.com", "https://rapidservice.example", "owner2@example.com"],
    ["City Wheel Works", "Brigade Road", "Bangalore", 12.97200000, 77.60600000, "08033445566", "citywheel@example.com", "https://citywheel.example", "owner3@example.com"],
    ["Bangalore Tyre Fix", "HSR Layout", "Bangalore", 12.91160000, 77.64840000, "08034567890", "tyrefix@example.com", "https://tyrefix.example", "owner4@example.com"],
    ["Metro Auto Garage", "Jayanagar", "Bangalore", 12.92500000, 77.59500000, "08031234567", "metroauto@example.com", "https://metroauto.example", "owner5@example.com"],
    ["Quick Repair Point", "Whitefield", "Bangalore", 12.96980000, 77.75000000, "08039876543", "quickrepair@example.com", "https://quickrepair.example", "owner6@example.com"],
    ["Express Auto Care", "Marathahalli", "Bangalore", 12.95920000, 77.69740000, "08031112222", "expressauto@example.com", "https://expressauto.example", "owner7@example.com"],
    ["Pro Wheel Service", "Banashankari", "Bangalore", 12.92550000, 77.57120000, "08033334444", "prowheel@example.com", "https://prowheel.example", "owner8@example.com"]
];

$stmt = $conn->prepare("INSERT INTO shops (shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$inserted = 0;
foreach ($shops as $shop) {
    $stmt->bind_param("sssdssss", $shop[0], $shop[1], $shop[2], $shop[3], $shop[4], $shop[5], $shop[6], $shop[7], $shop[8]);
    if ($stmt->execute()) {
        $inserted++;
    }
}
$stmt->close();

$result = $conn->query("SELECT COUNT(*) AS c FROM shops WHERE district='Bangalore'");
$row = $result->fetch_assoc();
$log = "Inserted $inserted Bangalore shops." . PHP_EOL;
$log .= "Bangalore shop count now: " . $row['c'] . PHP_EOL;
file_put_contents(__DIR__ . '/bangalore_shops_import.log', $log);
echo $log;
$conn->close();
