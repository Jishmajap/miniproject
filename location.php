<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Shop</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
    function getLocation() {
        console.log("Getting location...");
        document.getElementById("locationStatus").textContent = "Requesting your location...";
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, function(error) {
                console.log("Geolocation error: ", error.code, error.message);
                if (error.code === 1) {
                    useFallbackLocation();
                } else {
                    showError({message: "Unable to access your location. Using Bangalore fallback."});
                    useFallbackLocation();
                }
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            });
        } else {
            showError({message: "Geolocation is not supported by this browser."});
            useFallbackLocation();
        }
    }

    function useFallbackLocation() {
        document.getElementById("currentLat").value = 12.9141;
        document.getElementById("currentLon").value = 77.7239;
        document.getElementById("locationDisplay").value = "12.9141, 77.7239";
        document.getElementById("locationStatus").textContent = "Using Bangalore fallback location.";
        fetchShops();
    }

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        console.log("Position obtained: ", lat, lon);
        document.getElementById("currentLat").value = lat;
        document.getElementById("currentLon").value = lon;
        document.getElementById("locationDisplay").value = `${lat.toFixed(4)}, ${lon.toFixed(4)}`;
        document.getElementById("locationStatus").textContent = "Using your current location.";
        fetchShops();
    }

    function fetchShops() {
        var lat = document.getElementById("currentLat").value;
        var lon = document.getElementById("currentLon").value;
        var location = document.getElementById("locationDisplay").value;

        if (!lat || !lon) {
            if (!location) {
                showError({message: "Please allow location access or enter a location."});
                return;
            }
            lat = "";
            lon = "";
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_nearby_shops.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                console.log("XHR status: ", xhr.status);
                console.log("XHR responseText: ", xhr.responseText);
                if (xhr.status == 200) {
                    try {
                        var responseText = xhr.responseText;
                        console.log("Response text: ", responseText);
                        var shops = JSON.parse(responseText);
                        console.log("Shops response: ", shops);
                        displayShops(shops);
                    } catch (e) {
                        showError({message: "Error parsing shops data."});
                        console.error("Parsing Error: ", e);
                    }
                } else {
                    showError({message: "Error fetching shops data. Status: " + xhr.status});
                }
            }
        };

        var params = "lat=" + encodeURIComponent(lat) + "&lon=" + encodeURIComponent(lon) + "&location=" + encodeURIComponent(location);
        xhr.send(params);
    }

    function displayShops(shops) {
        let table = document.getElementById("shopTable");
        table.innerHTML = `
            <tr>
                <th>Shop Name</th>
                <th>Address</th>
                <th>District</th>
                <th>Distance (km)</th>
                <th>Action</th>
            </tr>
        `;

        if (shops.error) {
            // Display error message if there's an error in the response
            let row = table.insertRow();
            let cell = row.insertCell(0);
            cell.colSpan = 5;
            cell.innerHTML = shops.error;
            cell.style.textAlign = "center";
        } else if (shops.length === 0) {
            // Handle case where no shops are found
            let row = table.insertRow();
            let cell = row.insertCell(0);
            cell.colSpan = 5;
            cell.innerHTML = "No shops found at this location.";
            cell.style.textAlign = "center";
        } else {
            for (let shop of shops) {
                let row = table.insertRow();
                row.insertCell(0).innerHTML = shop.shop_name;
                row.insertCell(1).innerHTML = shop.address;
                row.insertCell(2).innerHTML = shop.district;
                row.insertCell(3).innerHTML = shop.distance || "N/A";
                let actionCell = row.insertCell(4);
                let button = document.createElement("button");
                button.innerHTML = "Send Request";
                button.onclick = function() {
                    window.location.href = `request.php?shop_name=${encodeURIComponent(shop.shop_name)}&shop_address=${encodeURIComponent(shop.address)}`;
                };
                actionCell.appendChild(button);
            }
        }
    }

    function showError(error) {
        document.getElementById("errorDisplay").innerHTML = error.message;
        document.getElementById("locationStatus").textContent = error.message + " You can still type your location manually and search.";
        console.error(error.message);
    }
    </script>
</head>
<body class="location-page" onload="getLocation();">
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
    <section id="location">
        <div class="loccontent">
            <h1>Find Your Service Now</h1>
            <br><br>
            <form class="locationform" onsubmit="fetchShops(); return false;">
                <button onclick="getLocation(); return false;" class="currentloc">Get Current Location</button>
                <br><br>
                <label for="locationDisplay">Enter your area or district</label>
                <input type="text" placeholder="Current Location" id="locationDisplay">
                <input type="hidden" id="currentLat">
                <input type="hidden" id="currentLon">
                <br><br>
                <div id="locationStatus"></div>
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
                    <th>Action</th>
                </tr>
            </table>
        </div>
    </section>
</body>
</html>