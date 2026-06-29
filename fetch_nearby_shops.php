<?php
function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float {
    $earthRadiusKm = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2)
        + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
        * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadiusKm * $c;
}

function getFallbackShops(float $lat, float $lon): array {
    $fallbackShops = [
        ['shop_name' => 'AutoCare Hub', 'address' => '12th Main, Koramangala', 'district' => 'Bangalore', 'latitude' => 12.9151, 'longitude' => 77.7245, 'phone_number' => '08023456789', 'email' => 'autocare@example.com', 'website' => 'https://autocare.example', 'owner_email' => 'owner1@example.com'],
        ['shop_name' => 'Rapid Service Center', 'address' => '1st Cross, Indiranagar', 'district' => 'Bangalore', 'latitude' => 12.9716, 'longitude' => 77.6412, 'phone_number' => '08022334455', 'email' => 'rapidservice@example.com', 'website' => 'https://rapidservice.example', 'owner_email' => 'owner2@example.com'],
        ['shop_name' => 'City Wheel Works', 'address' => 'Brigade Road', 'district' => 'Bangalore', 'latitude' => 12.9720, 'longitude' => 77.6060, 'phone_number' => '08033445566', 'email' => 'citywheel@example.com', 'website' => 'https://citywheel.example', 'owner_email' => 'owner3@example.com'],
        ['shop_name' => 'Bangalore Tyre Fix', 'address' => 'HSR Layout', 'district' => 'Bangalore', 'latitude' => 12.9116, 'longitude' => 77.6484, 'phone_number' => '08034567890', 'email' => 'tyrefix@example.com', 'website' => 'https://tyrefix.example', 'owner_email' => 'owner4@example.com'],
        ['shop_name' => 'Metro Auto Garage', 'address' => 'Jayanagar', 'district' => 'Bangalore', 'latitude' => 12.9250, 'longitude' => 77.5950, 'phone_number' => '08031234567', 'email' => 'metroauto@example.com', 'website' => 'https://metroauto.example', 'owner_email' => 'owner5@example.com']
    ];

    foreach ($fallbackShops as &$shop) {
        $shop['distance'] = round(calculateDistanceKm($lat, $lon, (float)$shop['latitude'], (float)$shop['longitude']), 2);
    }
    unset($shop);

    usort($fallbackShops, function ($a, $b) {
        return ($a['distance'] ?? 0.0) <=> ($b['distance'] ?? 0.0);
    });

    return array_slice($fallbackShops, 0, 5);
}

$lat = 0.0;
$lon = 0.0;
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

$shops = [];
$conn = new mysqli('localhost', 'root', '', 'shop_management');
if ($conn && !$conn->connect_error) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'shops'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $result = $conn->query("SELECT id, shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email FROM shops");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['distance'] = round(calculateDistanceKm($lat, $lon, (float)$row['latitude'], (float)$row['longitude']), 2);
                if ((float)$row['distance'] <= 20) {
                    $shops[] = $row;
                }
            }
        }
    }
    $conn->close();
}

if (empty($shops)) {
    $shops = getFallbackShops($lat != 0.0 && $lon != 0.0 ? $lat : 12.9141, $lon != 0.0 ? $lon : 77.7239);
}

usort($shops, function ($a, $b) {
    return ($a['distance'] ?? 0.0) <=> ($b['distance'] ?? 0.0);
});

echo json_encode(array_slice($shops, 0, 5));
