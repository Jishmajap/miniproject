<?php
session_start();
include 'admin/db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$shop_name = trim($_GET['shop_name'] ?? $_POST['shop_name'] ?? '');
$shop_address = trim($_GET['shop_address'] ?? $_POST['shop_address'] ?? '');
$name = trim($_SESSION['username'] ?? '');
$email = trim($_SESSION['email'] ?? '');
$phone = '';
$current_location = '';
$service_needed = '';
$message = '';

$conn->query("CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL DEFAULT 0,
    shop_name VARCHAR(255) NOT NULL,
    shop_address TEXT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    current_location TEXT NOT NULL,
    service_needed TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL DEFAULT 'Pending'
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_name = trim($_POST['shop_name'] ?? '');
    $shop_address = trim($_POST['shop_address'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $current_location = trim($_POST['current_location'] ?? '');
    $service_needed = trim($_POST['service_needed'] ?? '');

    if ($shop_name === '' || $name === '' || $email === '' || $phone === '' || $current_location === '' || $service_needed === '') {
        $message = 'Please fill in every field before submitting the request.';
    } else {
        $shop_id = 0;
        $stmt = $conn->prepare("SELECT id FROM shops WHERE shop_name = ? OR address = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $shop_name, $shop_address);
            $stmt->execute();
            $stmt->bind_result($shop_id_from_db);
            if ($stmt->fetch()) {
                $shop_id = (int)$shop_id_from_db;
            }
            $stmt->close();
        }

        $status = 'Pending';
        $success = false;
        $insertError = '';

        $stmt = $conn->prepare("INSERT INTO service_requests (shop_id, shop_name, shop_address, name, email, phone, current_location, service_needed, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issssssss", $shop_id, $shop_name, $shop_address, $name, $email, $phone, $current_location, $service_needed, $status);
            $success = $stmt->execute();
            if (!$success) {
                $insertError = $stmt->error;
            }
            $stmt->close();
        } else {
            $insertError = $conn->error;
        }

        if ($success) {
            $message = 'Service request submitted successfully.';
            header('Location: location.php');
            exit;
        }

        $message = $insertError !== '' ? 'Error submitting service request: ' . $insertError : 'Error submitting service request. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
    function getCurrentLocation() {
        if (!navigator.geolocation) {
            showLocationError("Geolocation is not supported by this browser.");
            return;
        }

        document.getElementById("locationError").textContent = "Requesting your location...";

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    const displayName = data.display_name || `${lat.toFixed(4)}, ${lon.toFixed(4)}`;
                    document.getElementById("current_location").value = displayName;
                    document.getElementById("locationError").textContent = "";
                })
                .catch(() => {
                    const fallback = `${lat.toFixed(4)}, ${lon.toFixed(4)}`;
                    document.getElementById("current_location").value = fallback;
                    document.getElementById("locationError").textContent = "";
                });
        }, function() {
            showLocationError("Permission denied or location unavailable.");
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        });
    }

    function showLocationError(message) {
        document.getElementById("locationError").textContent = message;
    }
    </script>
</head>
<body>
    <h1>Service Request</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="request.php" method="post">
        <input type="hidden" name="shop_name" value="<?php echo htmlspecialchars($shop_name); ?>">
        <input type="hidden" name="shop_address" value="<?php echo htmlspecialchars($shop_address); ?>">

        <?php if ($shop_name !== ''): ?>
            <p><strong>Selected Shop:</strong> <?php echo htmlspecialchars($shop_name); ?></p>
        <?php endif; ?>
        <?php if ($shop_address !== ''): ?>
            <p><strong>Shop Address:</strong> <?php echo htmlspecialchars($shop_address); ?></p>
        <?php endif; ?>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required><br><br>

        <label for="current_location">Current Location:</label>
        <button type="button" onclick="getCurrentLocation()">Get Current Location</button><br><br>
        <input type="text" id="current_location" name="current_location" value="<?php echo htmlspecialchars($current_location); ?>" required><br><br>
        <div id="locationError" style="color: red; margin-bottom: 10px;"></div>

        <label for="service_needed">Service Needed:</label>
        <textarea id="service_needed" name="service_needed" rows="4" required><?php echo htmlspecialchars($service_needed); ?></textarea><br><br>

        <input type="submit" value="Submit Request">
        <a href="location.php" style="margin-left: 10px;">Back to Find Shops</a>
    </form>
</body>
</html>