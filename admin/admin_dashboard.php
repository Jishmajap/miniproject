<?php
session_start();

// Include database connection
include './db_connection.php';

// Fetch data for the overview section
$total_shops = $conn->query("SELECT COUNT(*) FROM shops")->fetch_row()[0];
$total_services = $conn->query("SELECT COUNT(*) FROM services")->fetch_row()[0];
$total_requests = $conn->query("SELECT COUNT(*) FROM service_requests")->fetch_row()[0];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
        <h2>Admin Dashboard</h2>
        <a href="admin_dashboard.php">Overview</a>
        <a href="admins/admin_shops.php">Shops</a>
        <a href="admins/admin_users.php">Users</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Manage shops, services, service requests, and users.</p>

        <div class="card">
            <h2>Overview</h2>
            <p>Total Shops: <?php echo $total_shops; ?></p>
            <p>Total Services: <?php echo $total_services; ?></p>
            <p>Total Service Requests: <?php echo $total_requests; ?></p>
        </div>


        <div class="card">
            <h2>Quick Actions</h2>
            <a href="./admins/admin_shops.php"><button>Add New Shop</button></a>
            <a href="./admins/admin_users.php"><button>Manage Users</button></a>
        </div>

    </div>
</body>
</html>
