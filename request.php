<?php
session_start();
include 'admin/db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$shop_name = isset($_GET['shop_name']) ? htmlspecialchars($_GET['shop_name']) : '';
$shop_address = isset($_GET['shop_address']) ? htmlspecialchars($_GET['shop_address']) : '';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = htmlspecialchars($_POST['shop_name']);
    $shop_address = htmlspecialchars($_POST['shop_address']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $current_location = htmlspecialchars($_POST['current_location']);
    $service_needed = htmlspecialchars($_POST['service_needed']);
    $status = 'Pending'; // Default status

    // Fetch shop_id based on shop_name
    $stmt = $conn->prepare("SELECT id FROM shops WHERE shop_name = ?");
    if ($stmt) {
        $stmt->bind_param("s", $shop_name);
        $stmt->execute();
        $stmt->bind_result($shop_id);
        $stmt->fetch();
        $stmt->close();

        if ($shop_id) {
            // Check if shop_id column exists in service_requests table
            $result = $conn->query("SHOW COLUMNS FROM service_requests LIKE 'shop_id'");
            if ($result && $result->num_rows > 0) {
                $stmt = $conn->prepare("INSERT INTO service_requests (shop_id, shop_name, shop_address, name, email, phone, current_location, service_needed, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("issssssss", $shop_id, $shop_name, $shop_address, $name, $email, $phone, $current_location, $service_needed, $status);
                    if ($stmt->execute()) {
                        $message = "Service request submitted successfully.";
                        header("Location: location.php");

                    } else {
                        $message = "Error submitting service request.";
                    }
                    $stmt->close();
                }
            } else {
                $message = "Service request table does not have shop_id column.";
            }
        } else {
            $message = "Shop not found.";
        }
    } else {
        $message = "Error preparing statement.";
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
</head>
<body>
    <h1>Service Request</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="request.php" method="post">
        <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>">
        <input type="hidden" name="shop_address" value="<?php echo $shop_address; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required><br><br>
        
        <label for="current_location">Current Location:</label>
        <input type="text" id="current_location" name="current_location" required><br><br>
        
        <label for="service_needed">Service Needed:</label>
        <textarea id="service_needed" name="service_needed" rows="4" required></textarea><br><br>
        
        <input type="submit" value="Submit Request">
    </form>
</body>
</html>