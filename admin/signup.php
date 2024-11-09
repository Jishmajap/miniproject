<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet"  href="adminstyle.css"> 

</head>
<body>
<section class="content">
<div id="container" class="card">
    <h2>Sign Up</h2>
    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'><center>" . $_SESSION['error'] . "</center></p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p style='color:green'><center>" . $_SESSION['success'] . "</center></p>";
        unset($_SESSION['success']);
    }
    ?>
    <form id="signupForm" action="signup_handler.php" method="POST">
        <label for="userType">Sign up as:</label>
        <select id="userType" name="userType" required>
            <option value="admin">Admin</option>
            <option value="shop">Shop</option>
        </select>
        <br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Signup">

<p class="logintext">Already have an account? <a href="login.php">Login</a></p>

    </form>
    </div>
        </section>
</body>
</html>