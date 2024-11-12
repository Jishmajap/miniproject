<?php
// Include database connection
include '../db_connection.php';

// Include login check
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch the shop details using the owner email
$sql_shop = "SELECT id FROM shops WHERE owner_email=?";
$stmt_shop = $conn->prepare($sql_shop);
$stmt_shop->bind_param("s", $email);
$stmt_shop->execute();
$stmt_shop->bind_result($shop_id);
$stmt_shop->fetch();
$stmt_shop->close();

// Check if shop_id was retrieved
if (!$shop_id) {
    die('No shop found for the given email.');
}

// Fetch service requests for the current shop
$sql_requests = "SELECT * FROM service_requests WHERE shop_id=?";
$stmt_requests = $conn->prepare($sql_requests);
$stmt_requests->bind_param("i", $shop_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
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
        .container {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
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
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h1>Service Requests</h1>
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
                        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
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
    </div>
</body>
</html>