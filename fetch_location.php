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

$district = $_POST['district'] ?? '';

if (empty($district)) {
    echo json_encode(["error" => "District is required"]);
    exit();
}

// Debugging: Log the received district
error_log("Received district: " . $district);

// SQL query to find shops within the specified district
$sql = "SELECT id, shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email FROM shops WHERE district LIKE CONCAT('%', ?, '%')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $district);
$stmt->execute();
$result = $stmt->get_result();

$shops = [];
while ($row = $result->fetch_assoc()) {
    $shops[] = $row;
}

// Debugging: Log the number of shops found
error_log("Number of shops found: " . count($shops));

if (empty($shops)) {
    echo json_encode(["error" => "No shops found in this district."]);
} else {
    echo json_encode($shops);
}

$stmt->close();
$conn->close();
?>