<?php
session_start();

// Include database connection
include '../db_connection.php';


// Handle form submission for adding a new shop
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_shop'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $district = $_POST['district'];
    
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $owner_email = $_POST['owner_email'];

    // Insert new shop into the database
    $stmt = $conn->prepare("INSERT INTO shops (shop_name, address, district,phone_number, email, website, owner_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $address, $district,$phone_number, $email, $website, $owner_email);

    if ($stmt->execute()) {
        // Redirect to the shops page after successful insertion
        header("Location: admin_shops.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for editing a shop
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_shop'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    // Update shop in the database
    $stmt = $conn->prepare("UPDATE shops SET shop_name = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $location, $id);

    if ($stmt->execute()) {
        // Redirect to the shops page after successful update
        header("Location: admin_shops.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deletion of a shop
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete associated service requests first
    $stmt = $conn->prepare("DELETE FROM service_requests WHERE shop_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete shop from the database
    $stmt = $conn->prepare("DELETE FROM shops WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the shops page after successful deletion
        header("Location: admin_shops.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch shops data
$shops_result = $conn->query("SELECT * FROM shops");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Shops</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
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
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .card form {
            display: flex;
            flex-direction: column;
        }

        .card label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .card input[type="text"],
        .card input[type="email"],
        .card textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .card input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .card input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="../admin_dashboard.php">Overview</a>
        <a href="admin_shops.php">Shops</a>
        <a href="admin_users.php">Users</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h1>Manage Shops</h1>
        <p>Here you can add, edit, and delete shop information.</p>

        <div class="card">
            <h2>Shops List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($shop = $shops_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $shop['id']; ?></td>
                        <td><?php echo $shop['shop_name']; ?></td>
                        <td><?php echo $shop['address']; ?></td>
                        <td>
                            <a href="admin_shops.php?edit=<?php echo $shop['id']; ?>">Edit</a>
                            <a href="admin_shops.php?delete=<?php echo $shop['id']; ?>">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
    <h2>Add New Shop</h2>
    <form action="admin_shops.php" method="POST">
        <label for="name">Shop Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea>
        
        <label for="district">District:</label>
        <input type="text" id="district" name="district" required>
        
     
   
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="website">Website:</label>
        <input type="text" id="website" name="website">
        
        <label for="owner_email">Owner Email:</label>
        <input type="email" id="owner_email" name="owner_email" required>
        
        <input type="submit" name="add_shop" value="Add Shop">
    </form>
</div>

        <?php if (isset($_GET['edit'])): ?>
        <?php
            $id = $_GET['edit'];
            $shop_result = $conn->query("SELECT * FROM shops WHERE id = $id");
            $shop = $shop_result->fetch_assoc();
        ?>
        <div class="card">
            <h2>Edit Shop</h2>
            <form action="admin_shops.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $shop['id']; ?>">
                <label for="name">Shop Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $shop['shop_name']; ?>" required>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $shop['address']; ?>" required>
                <input type="submit" name="edit_shop" value="Update Shop">
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>