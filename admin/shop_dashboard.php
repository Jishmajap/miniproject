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
        <a href="shop_profile.php">Profile</a>
        <a href="#" data-target="recent_services">Service Orders</a>
        <a href="#" data-target="available_services">Available Services</a>
        <a href="#" data-target="shop_settings">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <h1>Welcome to the Car Service Shop Dashboard</h1>
        <p>Manage your shop, view service orders, and update your profile.</p>

        <div class="card" id="recent_services">
            <h2>Recent Service Requests</h2>
            <table>
                <tr>
                    <th>Shop Name</th>
                    <th>Shop Address</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Current Location</th>
                    <th>Service Needed</th>
                    <th>Timestamp</th>
                </tr>
                <?php
                include 'db_connection.php';

                $sql = "SELECT shop_name, shop_address, name, email, phone, current_location, service_needed, timestamp FROM service_requests ORDER BY timestamp DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['shop_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['shop_address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['current_location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_needed']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No service requests found</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>

        <div class="card" id="available_services">
            <h2>Available Services</h2>
            <table>
                <tr>
                    <th>Service ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
                <tr>
                    <td>2001</td>
                    <td>Oil Change</td>
                    <td>Complete oil change with filter replacement</td>
                    <td>$40.00</td>
                </tr>
                <tr>
                    <td>2002</td>
                    <td>Tire Rotation</td>
                    <td>Rotation of all four tires</td>
                    <td>$30.00</td>
                </tr>
                <!-- Add more rows as needed -->
            </table>
        </div>

        <div class="card" id="shop_settings">
            <h2>Shop Settings</h2>
            <p>Update your shop settings and preferences.</p>
            <form action="shop_settings_process.php" method="post">
                <label for="shop_name">Shop Name:</label>
                <input type="text" id="shop_name" name="shop_name" value="My Car Service Shop" required><br><br>
                
                <label for="shop_email">Shop Email:</label>
                <input type="email" id="shop_email" name="shop_email" value="shop@example.com" required><br><br>
                
                <label for="shop_address">Shop Address:</label>
                <textarea id="shop_address" name="shop_address" rows="4" required>123 Main St, Anytown, USA</textarea><br><br>
                
                <input type="submit" value="Save Settings">
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.sidebar a[data-target]');

        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>