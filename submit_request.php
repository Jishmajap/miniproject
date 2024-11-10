<?php
session_start();

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

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shop_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO service_requests (shop_name, shop_address, name, email, phone, current_location, service_needed) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $shop_name, $shop_address, $name, $email, $phone, $current_location, $service_needed);

    if ($stmt->execute()) {
        header("Location: location.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>