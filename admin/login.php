<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query based on user type
    if ($userType == 'admin') {
        $query = "SELECT * FROM admins WHERE email = ?";
    } else {
        $query = "SELECT * FROM shop_owners WHERE email = ?";
    }

    // Create a prepared statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // User authenticated successfully
                $_SESSION['email'] = $email;
                $_SESSION['userType'] = $userType;

                if ($userType == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: shop_dashboard.php");
                }
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            // No user found with that email
            $_SESSION['error'] = "Invalid email or password.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="adminstyle.css">
</head>
<body>
<section class="content">
<div id="container" class="card">
    <h2>Login</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'><center>" . $_SESSION['error'] . "</center></p>";
        unset($_SESSION['error']);
    }
    ?>
    <form id="loginForm" action="login.php" method="POST">
        <label for="userType">Login as:</label>
        <select id="userType" name="userType" required>
            <option value="admin">Admin</option>
            <option value="shop">Shop</option>
        </select>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Login">
        <p class="signuptext">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>
</div>
</section>
</body>
</html>