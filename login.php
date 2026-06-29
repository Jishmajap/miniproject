<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn->query("CREATE DATABASE IF NOT EXISTS `" . $dbname . "`")) {
    die("Failed to create database: " . $conn->error);
}

if (!$conn->select_db($dbname)) {
    die("Failed to select database: " . $conn->error);
}

$conn->query("CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
)");

$conn->set_charset("utf8mb4");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error_message = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['loggedin'] = true;
                    header("Location: index.php");
                    exit();
                } else {
                    $error_message = "Invalid password.";
                }
            } else {
                $error_message = "No user found with this email.";
            }

            $stmt->close();
        } else {
            $error_message = "Database query failed: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
        <!-- navbar -->
        <ul class="navul">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <li class="navli">
            <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <ul class="dropdown">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </li>
    <?php else: ?>
        <li class="navli"><a href="login.php">Login</a></li>
    <?php endif; ?>
    <li class="navli"><a href="location.php">Find Now</a></li>
    <li class="navli"><a href="index.php#contact">Contact</a></li>
    <li class="navli"><a href="index.php#about">About</a></li>
    <li class="navli"><a href="index.php">Home</a></li>
    </ul>
        
        <section class="content">
                <div id="container" class="card">
                        <h1>Login</h1>
                        <?php if (isset($error_message)): ?>
                            <p class="error"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                        <form action="login.php" method="post">  
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" placeholder="username" required><br><br>
                                
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" placeholder="password" required><br><br>
                                
                                <input type="submit" value="Login">
                                <p class="signuptext">Don't have an account? <a href="signup.php">Sign Up</a></p>
                        </form>
                </div>
        </section>
           
</body>
</html>
