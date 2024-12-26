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
    $name = $_POST['name'];
    $email = $_POST['emaiid'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Check if passwords match
    if ($password !== $confirmpassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $password = $hashed_password;

        // Insert user into database
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
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
        <title>Signup</title>
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
                        <h1>SignUp</h1>
                        <?php if (isset($error_message)): ?>
                            <p class="error"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                        <form action="signup.php" method="post">  
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="name" required><br><br>
                        
                            <label for="email">Email-ID:</label>
                            <input type="email" id="emaiid" name="emaiid" placeholder="abhijith@example.com" required><br><br>
                            
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="password" required><br><br>
                            
                            <label for="confirmpassword">Confirm Password:</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="confirm password" required><br><br>
                            
                            <input type="submit" value="Signup">

                            <p class="logintext">Already have an account? <a href="login.php">Login</a></p>
                        </form>
                </div>
        </section>
           
</body>
</html>
