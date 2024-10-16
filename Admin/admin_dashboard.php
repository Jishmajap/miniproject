<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch shop details
$sql = "SELECT * FROM shops WHERE id = 1"; // Assuming there's only one shop for simplicity
$result = $mysqli->query($sql);
$shop = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = $_POST['shop_name'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $sql = "UPDATE shops SET shop_name = ?, location = ?, description = ? WHERE id = 1";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sss", $shop_name, $location, $description);
        if ($stmt->execute()) {
            $success = "Shop details updated successfully.";
        } else {
            $error = "Error updating shop details: " . $stmt->error;
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Edit Shop Details</h2>
    <form action="admin_dashboard.php" method="post">
        <label for="shop_name">Shop Name:</label>
        <input type="text" id="shop_name" name="shop_name" value="<?php echo htmlspecialchars($shop['shop_name']); ?>" required><br><br>
        
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($shop['location']); ?>" required><br><br>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($shop['description']); ?></textarea><br><br>
        
        <input type="submit" value="Update">
    </form>
    <?php if (isset($success)): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <a href="admin_logout.php">Logout</a>
</body>
</html>