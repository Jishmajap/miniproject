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
                <li class="navli"><a href="login.php">Login</a></li>
                <li class="navli"><a href="vehicle.php#contact">Contact</a></li>
                <li class="navli"><a href="vehicle.php#about">About </a></li>
                <li class="navli"><a href="vehicle.php">Home</a></li>
        </ul>
        
        <section class="content">
                <div id="container" class="card">
                        <h1>Login</h1>
                        <form action="login_process.php" method="post">  
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
