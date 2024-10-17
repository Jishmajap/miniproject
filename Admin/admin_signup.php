<?php
session_start();
require_once 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['emaiid'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin into database
        $sql = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                $success = "Admin registered successfully. You can now <a href='admin_login.php'>login</a>.";
                header("location: admin_login.php");
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error: " . $mysqli->error;
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <section class="verif_body">
            <h1>Admin Signup</h1>
            <div class="card">
                <form action="admin_signup.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="name" required><br><br>
                        
                    <label for="email">Email-ID:</label>
                        <input type="email" id="emaiid" name="emaiid" placeholder="abhijith@example.com" required><br><br>
                            
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
                    
                    <input type="submit" value="Signup">
                </form>
                <p class="trylogin">Already have an account? <a href="admin_login.php">Login</a></p>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p><?php echo $success; ?></p>
                <?php endif; ?>
            </div>
        </section>
</body>
</html>