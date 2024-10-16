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
                        <form action="signup_process.php" method="post">  
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
