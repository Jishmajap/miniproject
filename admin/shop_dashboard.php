<?php
// Include database connection
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the email from the session
$email = $_SESSION['email'];

// Fetch the shop details using the owner email
$sql_shop = "SELECT id, shop_name, address, district, latitude, longitude, phone_number, email, website, owner_email FROM shops WHERE owner_email=?";
$stmt_shop = $conn->prepare($sql_shop);
if ($stmt_shop === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_shop->bind_param("s", $email);
$stmt_shop->execute();
$stmt_shop->bind_result($shop_id, $shop_name, $address, $district, $latitude, $longitude, $phone_number, $shop_email, $website, $owner_email);
$stmt_shop->fetch();
$stmt_shop->close();

// Check if shop_id was retrieved
if (!$shop_id) {
    die('No shop found for the given email.');
}

// Fetch available services for the current shop
$sql_services = "SELECT * FROM services WHERE shop_id=?";
$stmt_services = $conn->prepare($sql_services);
if ($stmt_services === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_services->bind_param("i", $shop_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();

// Fetch service requests for the current shop
$sql_requests = "SELECT * FROM service_requests WHERE email=?";
$stmt_requests = $conn->prepare($sql_requests);
if ($stmt_requests === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_requests->bind_param("i", $email);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Service Shop Dashboard</title>
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
        <a href="shop_dashboard.php">Dashboard</a>
        <a href="service/shop_service_request.php">Service Requests</a>
        <a href="service/shop_available_service.php">Available Services</a>
        <a href="service/shop_settings.php">Settings</a>
        <a href="../admin/logout.php">Logout</a>
    </div>
    <div class="content">
        <h1>Welcome to the Car Service Shop Dashboard</h1>
        <p>Manage your shop, view service orders, and update your profile.</p>

        <div class="card" id="recent_services">
            <h2>Recent Service Requests</h2>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Current Location</th>
                    <th>Service Needed</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                </tr>
                <?php
                if ($result_requests->num_rows > 0) {
                    while($row = $result_requests->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['current_location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_needed']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No service requests found</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="card" id="available_services">
            <h2>Available Services</h2>
            <table>
                <tr>
                    <th>Service Name</th>
                    <th>Price</th>
                </tr>
                <?php
                if ($result_services->num_rows > 0) {
                    while ($row = $result_services->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No services available</td></tr>";
                }

                $stmt_services->close();
                $stmt_requests->close();
                $conn->close();
                ?>
            </table>
        </div>

    </div>
</body>
</html>