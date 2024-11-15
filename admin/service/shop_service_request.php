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

// Fetch service requests for the current shop
$sql_requests = "SELECT * FROM service_requests WHERE email=?";
$stmt_requests = $conn->prepare($sql_requests);
if ($stmt_requests === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_requests->bind_param("s", $email);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
if ($result_requests === false) {
    die('Execute failed: ' . htmlspecialchars($stmt_requests->error));
}

// Check if the form is submitted to update the status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status'];

    // Update the status in the database
    $sql_update = "UPDATE service_requests SET status=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt_update->bind_param("si", $new_status, $request_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Redirect to the same page to reflect the changes
    header("Location: shop_service_request.php");
    exit();
}
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
        <a href="../shop_settings.php">Settings</a>
        <a href="../admin/logout.php">Logout</a>
    </div>
    <div class="content">
        <div class="card" id="service_requests">
            <h2>Service Requests</h2>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Current Location</th>
                    <th>Service Needed</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th>Action</th>
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
                        echo "<td>";
                        echo "<form action='shop_service_request.php' method='post'>";
                        echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<select name='status'>";
                        echo "<option value='Pending'" . ($row['status'] == 'Pending' ? ' selected' : '') . ">Pending</option>";
                        echo "<option value='In Progress'" . ($row['status'] == 'In Progress' ? ' selected' : '') . ">In Progress</option>";
                        echo "<option value='Completed'" . ($row['status'] == 'Completed' ? ' selected' : '') . ">Completed</option>";
                        echo "</select>";
                        echo "<input type='submit' name='update_status' value='Update'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No service requests found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>