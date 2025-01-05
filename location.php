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
        fetchLocationFromServer(lat, lon);
    }

    function fetchLocationFromServer(lat, lon) {
        var xhr = new XMLHttpRequest();
        var url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;
        
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log("Nominatim response: ", response);
                    if (response.address) {
                        var address = response.address.village || response.address.town || response.address.city || response.address.state_district || response.address.state;
                        var district = response.address.state_district || response.address.state;
                        document.getElementById("locationDisplay").value = `${address}, ${district}`;
                    } else {
                        showError({message: "Unable to retrieve location details."});
                    }
                } catch (e) {
                    showError({message: "Error parsing location data."});
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

        
        // Extract the district part after the comma
        var district = location.split(", ").pop();
        // echo district
        
        // Debugging: Log the extracted district value
        console.log("Extracted district:", district);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_location.php", true);
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
        xhr.send("district=" + encodeURIComponent(district));  // Send district as URL-encoded
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
        console.error(error.message);
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
            <h1>Find Your Service Now</h1>
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
                    <th>Action</th>
                </tr>
            </table>
        </div>
    </section>
</body>
</html>