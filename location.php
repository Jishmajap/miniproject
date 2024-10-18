<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
        <section id="location">
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

                <div class="loccontent">
                        <h1>Find Your service </br> Now</h1>
                        <br><br>
                        <form class="locationform">
                        <button onclick="getLocation(); return false;" class="currentloc">Get Current Location</button>
                        <p id="curloc" ></p>
                        <!-- <input type="text" placeholder="Mobile Number"></input><br><br> -->
                        <input type="submit" value="Search" >
                        </form>
                        

                        <p class = "or">---- OR ----</p>
                        <br>

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
                                <!-- <input type="text" placeholder="Mobile Number"></input><br><br> -->
                                <input type="submit" value="Search" >
                        </form>
                </div>
        </section>


        <script>
                function getLocation() {
                        if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(showPosition);
                        } else {
                                document.getElementById("curloc").innerHTML = "Geolocation is not supported by this browser.";
                        }
                }

                function showPosition(position) {
                        var curloc = document.getElementById("curloc");
                        curloc.innerHTML = 
                                "Latitude: " + position.coords.latitude + 
                                "<br>Longitude: " + position.coords.longitude;
                        curloc.classList.add("curloc");
                }
        </script>
</body>
</html>
