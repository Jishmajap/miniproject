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
    <script>

    function getLocation() {
        console.log("Getting location...");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            showError({message: "Geolocation is not supported by this browser."});
        }
    }

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        console.log("Position obtained: ", lat, lon);
        // Send the location to the server
        fetchLocationFromServer(lat, lon);
    }

    function fetchLocationFromServer(lat, lon) {
        var xhr = new XMLHttpRequest();
        var url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;
        
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                console.log("Nominatim response: ", response);
                if (response.address) {
                    var address = response.address.village || response.address.town || response.address.city || response.address.state_district || response.address.state;
                    var district = response.address.state_district || response.address.state;
                    document.getElementById("locationDisplay").value = `${address}, ${district}`;
                } else {
                    showError({message: "Unable to retrieve location details."});
                }
            }
        };
        xhr.send();
    }

    function fetchShops() {
        var location = document.getElementById("locationDisplay").value;
        if (!location) {
            showError({message: "Please enter a location."});
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_location.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var shops = JSON.parse(xhr.responseText);
                console.log("Shops response: ", shops);
                displayShops(shops);
            }
        };
        // Assuming the location is in the format "address, district"
        var parts = location.split(", ");
        var district = parts[parts.length - 1];
        xhr.send("district=" + encodeURIComponent(district));
    }

    function fetchShops() {
    fetch('fetch_shops.php')
        .then(response => response.json())
        .then(data => {
            let table = document.getElementById("shopTable");
            // Clear existing rows except the header
            table.innerHTML = `
                <tr>
                    <th>Shop Name</th>
                    <th>Address</th>
                    <th>District</th>
                    <th>Distance (km)</th>
                </tr>
            `;
            for (let shop of data) {
                let row = table.insertRow();
                row.insertCell(0).innerHTML = shop.name;
                row.insertCell(1).innerHTML = shop.address;
                row.insertCell(2).innerHTML = shop.district;
                row.insertCell(3).innerHTML = shop.distance;
            }
        })
        .catch(error => {
            document.getElementById("errorDisplay").innerHTML = "Error fetching shop data.";
            console.error('Error:', error);
        });
}
</script>
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

            <form class="locationform" onsubmit="fetchShops(); return false;">
                <button onclick="getLocation(); return false;" class="currentloc">Get Current Location</button>
                <br><br>
                <input type="text" placeholder="Current Location" id="locationDisplay"></input>
                <br><br>
                <input type="submit" value="Search">
                <br><br>
                <div id="errorDisplay" style="color: red;"></div>
            <div id="shopResults"></div>
            </form>
            <table id="shopTable" border="1">
                <tr>
                    <th>Shop Name</th>
                    <th>Address</th>
                    <th>District</th>
                    <th>Distance (km)</th>
                </tr>
            </table>
        </div>
    </section>
</body>
</html>