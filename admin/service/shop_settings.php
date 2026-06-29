<?php
// Include database connection
include '../db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


// Get the email from the session
$email = $_SESSION['email'];

// Fetch all shops associated with the owner email
$sql_shops = "SELECT shop_name FROM shops WHERE owner_email=?";
$stmt_shops = $conn->prepare($sql_shops);
if ($stmt_shops === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_shops->bind_param("s", $email);
$stmt_shops->execute();
$result_shops = $stmt_shops->get_result();
$shops = [];
while ($row = $result_shops->fetch_assoc()) {
    $shops[] = $row;
}
$stmt_shops->close();

// Fetch the selected shop details if a shop is selected
$selected_shop_name = isset($_POST['shop_name']) ? $_POST['shop_name'] : (isset($_GET['shop_name']) ? $_GET['shop_name'] : null);
if ($selected_shop_name) {
    $sql_shop = "SELECT shop_name, address, district, latitude, longitude, phone_number, email, website FROM shops WHERE shop_name=? AND owner_email=?";
    $stmt_shop = $conn->prepare($sql_shop);
    if ($stmt_shop === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt_shop->bind_param("ss", $selected_shop_name, $email);
    $stmt_shop->execute();
    $stmt_shop->bind_result($shop_name, $address, $district, $latitude, $longitude, $phone_number, $shop_email, $website);
    $stmt_shop->fetch();
    $stmt_shop->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_shop'])) {
    // Get the form data
    $shop_name = $_POST['shop_name'];
    $shop_email = $_POST['shop_email'];
    $address = $_POST['shop_address'];
    $district = $_POST['district'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $phone_number = $_POST['phone_number'];
    $website = $_POST['website'];

    // Update the shop details in the database
    $sql_update = "UPDATE shops SET address=?, district=?, latitude=?, longitude=?, phone_number=?, email=?, website=? WHERE shop_name=? AND owner_email=?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt_update->bind_param("sssssssss", $address, $district, $latitude, $longitude, $phone_number, $shop_email, $website, $shop_name, $email);
    $stmt_update->execute();
    $stmt_update->close();

    // Redirect back to the settings page with a success message
    header("Location: shop_settings.php?update=success&shop_name=" . urlencode($shop_name));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Settings</title>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 0;
            margin: 10px 0;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .card p {
            margin: 10px 0;
        }
        .card table {
            width: 100%;
            border-collapse: collapse;
        }
        .card table, .card th, .card td {
            border: 1px solid #ddd;
        }
        .card th, .card td {
            padding: 8px;
            text-align: left;
        }
        .card th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Car Service Shop</h2>
        <a href="../shop_dashboard.php">Dashboard</a>
        <a href="shop_service_request.php">Service Requests</a>
        <a href="shop_available_service.php">Available Services</a>
        <a href="shop_settings.php">Settings</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <div class="card" id="shop_settings">
            <h2>Shop Settings</h2>
            <p>Update your shop settings and preferences.</p>
            <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
                <p style="color: green;">Settings updated successfully!</p>
            <?php endif; ?>
            <form action="shop_settings.php" method="post">
                <label for="shop_name">Select Shop:</label>
                <select id="shop_name" name="shop_name" onchange="this.form.submit()">
                    <option value="">Select a shop</option>
                    <?php foreach ($shops as $shop): ?>
                        <option value="<?php echo htmlspecialchars($shop['shop_name']); ?>" <?php echo ($selected_shop_name == $shop['shop_name']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($shop['shop_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <?php if ($selected_shop_name): ?>
                <form action="shop_settings.php" method="post">
                    <input type="hidden" name="shop_name" value="<?php echo htmlspecialchars($selected_shop_name); ?>">
                    <label for="shop_name">Shop Name:</label>
                    <input type="text" id="shop_name" name="shop_name" value="<?php echo htmlspecialchars($shop_name); ?>" required><br><br>
                    
                    <label for="shop_email">Shop Email:</label>
                    <input type="email" id="shop_email" name="shop_email" value="<?php echo htmlspecialchars($shop_email); ?>" required><br><br>
                    
                    <label for="shop_address">Shop Address:</label>
                    <textarea id="shop_address" name="shop_address" rows="4" required><?php echo htmlspecialchars($address); ?></textarea><br><br>
                    
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($district); ?>" required><br><br>
                    
                    <label for="latitude">Latitude:</label>
                    <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($latitude); ?>" required><br><br>
                    
                    <label for="longitude">Longitude:</label>
                    <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($longitude); ?>" required><br><br>
                    
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required><br><br>
                    
                    <label for="website">Website:</label>
                    <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($website); ?>"><br><br>
                    
                    <input type="submit" name="update_shop" value="Update Settings">
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>