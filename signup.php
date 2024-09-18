<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="signup.css">
</head>
<body>
        <!-- navbar -->
        <ul>
                <li><a href="login.php">Login</a></li>
                <li ><a  href="#about">About </a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="vehicle.php">Home</a></li> 
        </ul> 
        
        <section class="content">
                <div id="container" class="card">
                        <h1>SignUp</h1>
                        <form action="login_process.php" method="post">  
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="name" required><br><br>
                        
                            <label for="email">Email-ID:</label>
                            <input type="email" id="emaiid" name="emaiid" placeholder="abhijith@example.com" required><br><br>
                            
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="password" required><br><br>
                            
                            <label for="confirmpassword">Confirm Password:</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="confirm password" required><br><br>
                            
                            <input type="submit" value="Login">

                            <p class="logintext">Already have an account? <a href="login.php">Login</a></p>
                        </form>
                </div>
        </section>
           
</body>
</html>
