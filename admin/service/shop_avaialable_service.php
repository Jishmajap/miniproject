<?php
// Include database connection
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the shop ID from the session
$shop_id = $_SESSION['shop_id'];

// Handle form submission to update services
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];

    $sql = "UPDATE services SET name=?, price=? WHERE id=? AND shop_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $service_name, $service_price, $service_id, $shop_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch available services for the current shop
$sql = "SELECT * FROM services WHERE shop_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <style>
        .container {
            padding: 20px;
            width: calc(100% - 270px); /* Adjusted to account for the sidebar width */
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
        <h1>Available Services</h1>
        <table>
            <tr>
                <th>Service Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>";
                        echo "<form method='post' action=''>";
                            echo "<input type='hidden' name='service_id' value='" . $row['id'] . "'>";
                            echo "<input type='text' name='service_name' value='" . htmlspecialchars($row['name']) . "' required>";
                            echo "<input type='number' name='service_price' value='" . htmlspecialchars($row['price']) . "' required>";
                            echo "<button type='submit' name='update_service' class='status-button'>Update</button>";
                        echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>
                    <td colspan='3'>No services available</td>
                </tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>