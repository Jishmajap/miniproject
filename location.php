<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>

        <ul class="navul">
                <li class="navli"><a href="login.php">Login</a></li>
                <li class="navli"><a href="index.php#contact">Contact</a></li>
                <li class="navli"><a href="index.php#about">About </a></li>
                <li class="navli"><a href="index.php">Home</a></li>
        </ul>

        <div class="content">
                <h1>Find Your service </br> Now</h1>
                <br><br>
                <form class="locationform">
                        <select class="city" value="city" name="City"  >
                        <option>District</option>
                        <option>Alappuzha</option>
                        <option>Ernakulam</option>
                        <option>Idukki</option>
                        <option>Kannur</option>
                        <option>kasaragod</option>
                        <option>Kollam</option>
                        <option>Kozhikode</option>
                        <option>Malappuram</option>
                        <option>Palakkad</option>
                        <option>Pathanamthitta</option>
                        <option>Thiruvananthapuram</option>
                        <option>Thrissur</option>
                        <option>Wayanad</option>
                        </select>
                        <br><br>
                        <input type="text" placeholder="Current Location"></input></br></br>
                        <input type="text" placeholder="Mobile Number"></input><br><br>
                        <input type="submit" value="Search" >
                </form>
        </div>
</body>
</html>
