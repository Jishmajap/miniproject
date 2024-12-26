<?php
session_start();
include 'admin/db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

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
                        header("Location: location.php");
                        exit;
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Error: 'shop_id' column does not exist in 'service_requests' table. Please update the database schema.";
            }
        } else {
            echo "Error: Shop not found.";
        }
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>