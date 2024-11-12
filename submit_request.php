<?php
session_start();
include 'db_connection.php';

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

    $stmt = $conn->prepare("INSERT INTO service_requests (shop_name, shop_address, name, email, phone, current_location, service_needed, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $shop_name, $shop_address, $name, $email, $phone, $current_location, $service_needed, $status);

    if ($stmt->execute()) {
        header("Location: shop_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>