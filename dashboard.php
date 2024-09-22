<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in. Redirecting to login page.";
    header("Location: login.php");
    exit();
}

// Debugging: Check session data
var_dump($_SESSION);

// Fetch user data from session
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="location.php">Location</a></li>
                <li><a href="service_request.php">Service Request</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="profile">
            <h2>User Profile</h2>
            <p>Name: <?php echo htmlspecialchars($user_name); ?></p>
            <p>Email: <?php echo htmlspecialchars($user_email); ?></p>
            <!-- Add more profile details as needed -->
        </section>
        <section class="actions">
            <h2>Actions</h2>
            <button onclick="window.location.href='location.php'">Go to Location</button>
            <button onclick="window.location.href='service_request.php'">Create Service Request</button>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Your Website. All rights reserved.</p>
    </footer>
</body>
</html>