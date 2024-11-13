<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$shop_name = isset($_GET['shop_name']) ? htmlspecialchars($_GET['shop_name']) : '';
$shop_address = isset($_GET['shop_address']) ? htmlspecialchars($_GET['shop_address']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<style></style>
<body>
    <h1>Service Request</h1>
    <form action="submit_request.php" method="post">
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