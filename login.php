<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and set session variables
            $_SESSION['username'] = $user['name']; // Store the user's name
            $_SESSION['loggedin'] = true;
            
            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with this email.";
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
