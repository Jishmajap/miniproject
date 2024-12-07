<?php
// Include database connection
include '../db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Get the email from the session
$email = $_SESSION['email'];

// Fetch the shop details using the owner email
$sql_shop = "SELECT id FROM shops WHERE owner_email=?";
$stmt_shop = $conn->prepare($sql_shop);
if ($stmt_shop === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_shop->bind_param("s", $email);
$stmt_shop->execute();
$stmt_shop->bind_result($shop_id);
$stmt_shop->fetch();
$stmt_shop->close();

// Check if shop_id was retrieved
if (!$shop_id) {
    die('No shop found for the given email.');
}

// Insert new service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $sql_insert = "INSERT INTO services (shop_id, service_name, price) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt_insert->bind_param("iss", $shop_id, $service_name, $price);
    $stmt_insert->execute();
    $stmt_insert->close();

    header("Location: shop_available_service.php");
    exit();
}

// Update existing service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_service'])) {
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $sql_update = "UPDATE services SET service_name=?, price=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt_update->bind_param("ssi", $service_name, $price, $service_id);
    $stmt_update->execute();
    $stmt_update->close();

    header("Location: shop_available_service.php");
    exit();
}

// Fetch available services for the current shop
$sql_services = "SELECT id, service_name, price FROM services WHERE shop_id=?";
$stmt_services = $conn->prepare($sql_services);
if ($stmt_services === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_services->bind_param("i", $shop_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
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
        <div class="card" id="available_services">
            <h2>Available Services</h2>
            <table>
                <tr>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result_services->num_rows > 0) {
                    while ($row = $result_services->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "<td><a href='shop_available_service.php?edit=" . $row['id'] . "'>Edit</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No services available</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="card" id="add_service">
            <h2><?php echo isset($_GET['edit']) ? 'Update Service' : 'Add New Service'; ?></h2>
            <form action="shop_available_service.php" method="post">
                <?php
                if (isset($_GET['edit'])) {
                    $service_id = $_GET['edit'];
                    $sql_edit = "SELECT service_name, price FROM services WHERE id=?";
                    $stmt_edit = $conn->prepare($sql_edit);
                    $stmt_edit->bind_param("i", $service_id);
                    $stmt_edit->execute();
                    $stmt_edit->bind_result($service_name, $price);
                    $stmt_edit->fetch();
                    $stmt_edit->close();
                    echo '<input type="hidden" name="service_id" value="' . $service_id . '">';
                }
                ?>
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" value="<?php echo isset($service_name) ? htmlspecialchars($service_name) : ''; ?>" required>
                <br>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" required>
                <br>
                <input type="submit" name="<?php echo isset($_GET['edit']) ? 'update_service' : 'add_service'; ?>" value="<?php echo isset($_GET['edit']) ? 'Update Service' : 'Add Service'; ?>">
            </form>
        </div>
    </div>
</body>
</html>

<?php
$stmt_services->close();
$conn->close();
?>