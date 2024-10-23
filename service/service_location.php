<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Service Centers Near You</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .input-group button {
            width: 20%;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        #service-centers-list {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Find Car Service Centers Near You</h2>
        <form id="locationForm">
            <div class="input-group">
                <input type="text" id="location" name="location" placeholder="Enter a city or address">
                <button type="submit">Search</button>
            </div>
        </form>
        <div id="map"></div>
        <div id="service-centers-list"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map;
        let serviceCentersList = document.getElementById('service-centers-list');

        // Initialize the map with a default view (coordinates of a central point)
        function initMap(lat, lng) {
            if (!map) {
                map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
            } else {
                map.setView([lat, lng], 13);
            }
        }

        // Fetch nearby car service centers using Overpass API
        function fetchServiceCenters(lat, lng) {
            const overpassUrl = `https://overpass-api.de/api/interpreter?data=[out:json];node[amenity=car_repair](around:5000,${lat},${lng});out;`;

            fetch(overpassUrl)
                .then(response => response.json())
                .then(data => {
                    // Clear previous results
                    serviceCentersList.innerHTML = `<h3>Nearby Car Service Centers:</h3>`;
                    data.elements.forEach(place => {
                        const listItem = document.createElement('div');
                        listItem.innerHTML = `<p><strong>${place.tags.name || 'Unnamed'}</strong><br>Lat: ${place.lat}, Lon: ${place.lon}</p>`;
                        serviceCentersList.appendChild(listItem);

                        // Add marker on map
                        L.marker([place.lat, place.lon]).addTo(map)
                            .bindPopup(`<strong>${place.tags.name || 'Unnamed'}</strong>`)
                            .openPopup();
                    });
                })
                .catch(error => console.error('Error fetching data from Overpass API:', error));
        }

        // Geocode the user input location and fetch nearby car service centers
        document.getElementById('locationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const location = document.getElementById('location').value;

            if (location) {
                const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`;

                fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;

                            // Initialize map and fetch service centers
                            initMap(lat, lon);
                            fetchServiceCenters(lat, lon);
                        } else {
                            alert('Location not found. Please try another.');
                        }
                    })
                    .catch(error => console.error('Error fetching geolocation:', error));
            } else {
                alert('Please enter a location.');
            }
        });
    </script>
</body>
</html>
