<?php
// Start a PHP session
session_start();

// Include the database configuration file
include 'config.php';

// Your API keys
$weatherApiKey = '33baef844850a57544e0d299279ba377';
$imageApiKey = 'ufcPADfNrO9oSj6lSRiGfXmSNFlDvgpUgXEt0PQ7K_4';
$bingMapsApiKey = 'AljLvnlaKmI3qfvjgCL99pMIBExHe653J8yuQzd_i1R75IBETOJNwBFtYjVKtbdq'; 

// Initialize variables
$city = 'Zagreb'; // Default placeholder city
$weatherData = null;
$imageUrl = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the city entered by the user
    $city = $_POST['city'];

    // Validate the city input
    if (empty($city)) {
        $error = 'Please enter a city name.';
    } else {
        // Weather API endpoint URL
        $weatherApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$weatherApiKey";

        // Make a GET request to the weather API
        $weatherResponse = file_get_contents($weatherApiUrl);

        // Parse the JSON response
        $weatherData = json_decode($weatherResponse, true);

        // Check if the weather request was successful
        if ($weatherData['cod'] !== 200) {
            $error = 'Failed to fetch weather data. Please check the city name and try again.';
            $weatherData = null;
        } else {
          $imageQuery = $city . ' city'; // Modify this as needed

          // URL encode the query
          $encodedQuery = urlencode($imageQuery);

          // Image API endpoint URL
          $imageApiUrl = "https://api.unsplash.com/photos/random?query=$encodedQuery&client_id=$imageApiKey";

          // Make a GET request to the image API
          $imageResponse = file_get_contents($imageApiUrl);

          // Parse the JSON response
          $imageData = json_decode($imageResponse, true);

            // Check if the image request was successful
            if (isset($imageData['urls']['regular'])) {
                $imageUrl = $imageData['urls']['regular'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: #0f2027; 
  background: -webkit-linear-gradient(to right, #0f2027, #203a43, #2c5364); 
  background: linear-gradient(to right, #0f2027, #203a43, #2c5364); 
}

.content {

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  height: 100vh;
  text-align: center;
  background: #2C3E50;  
  background: -webkit-linear-gradient(to right, #4CA1AF, #2C3E50);  
  background: linear-gradient(to right, #4CA1AF, #2C3E50); 

  max: width 10%;;
}

h1, h2, p {
  color: white;
}

.navbar {
  background-color: white;
  margin-top: 20px;
  padding: 0px;
}

.navbar ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.navbar li {
  display: inline-block;
  margin-right: 10px;
  position: relative;
}

.navbar li:hover {
  background-color: gold;
}

.navbar a {
  font-family: Arial, sans-serif;
  font-weight: bold;
  font-size: 16px;
  display: block;
  padding: 10px;
  text-decoration: none;
  color: black;
  transition: color 0.3s ease-in-out;
}

.navbar a:hover {
  color: green;
}

.subnav {
  display: none;
  background-color: gold;
  padding: 0px;
  position: absolute;
  top: 100%;
  left: 0;
  white-space: nowrap;
}

.navbar li:hover .subnav {
  display: flex;
}

.weather-heading {
  margin-top: 10px; 
  color: white;
}

.weather-section {
  margin-top: 20px; 
}

.weather-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  margin-top: 20px;
}

.weather-info {
  
  flex: 1 1 300px; 
  margin-right: 20px;
}

.image-container {
  width: 300px;
  height: 300px;
  overflow: hidden;
  margin-top: 20px;
  float: left;
  flex: 1 1 300px; 

}

.form-group {
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-group input {
  margin-right: 10px; 
}

.city-image {
  width: 400px;
  height: 300px;
}

.map-container {
  width: 400px;
  height: 300px;
  float: left;
  flex: 1 1 300px; 
  margin-top: 20px;
}

.footer {
  background-color: white;
  padding: 20px;
  color: black;
  text-align: center;
  position: fixed;
  bottom: 0;
  width: 100%;
}

.footer a {
  font-family: Arial, sans-serif;
  color: black;
  text-decoration: none;
}

.footer .github-icon {
  display: inline-block;
  width: 20px;
  height: 20px;
  vertical-align: middle;
  fill: black;
}

.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
  }

  .btn:hover {
    background-color: gold;
    color:black;
  }
</style>
</head>
<body>
<div style="display: flex; margin: 0.1rem; justify-content: center;">
  <h1 style="font-size: 3rem; font-style: italic; font-family: Arial, sans-serif; font-weight: bold; word-spacing: -0.9rem;">
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold;">VIJESTI</span>
    <span style="color: #ffd000; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold;">24/7</span>
  </h1>
</div>

<div class="navbar">
  <ul>
    <li><a href="homepage.php">Home</a></li>
    <li>
      <a href="news.php">News</a>
      <div class="subnav">
        <a href="politics.php">Politics</a>
        <a href="tech.php">Tech</a>
        <a href="sports.php">Sports</a>
      </div>
    </li>
    <li><a href="about.php">About</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="gallery.php">Gallery</a></li>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // User is logged in
        echo '<li><a href="weather.php">Weather</a></li>';
        echo '<li><a href="movies.php">Movies</a></li>';
        echo '<li><a href="profile.php">Profile</a></li>';
        echo '<li><a href="logout.php">Logout</a></li>';

        if ($_SESSION['is_admin'] === 1) {
            // User has admin privileges
            echo '<li><a href="admin.php">Admin</a></li>';
            echo '<li><a href="publish.php">Publish</a></li>';
        }
    } else {
        // User is not logged in
        echo '<li><a href="login.php">Login</a></li>';
        echo '<li><a href="register.php">Register</a></li>';
    }
    ?>
  </ul>
</div>

<div class="content">
  <h1 style="color: white;">Weather</h1>

  <form method="post" class="weather-form">
    <label for="city" style="color: white;">Enter City:</label>
    <div class="form-group">
      <input type="text" id="city" name="city" placeholder="E.g., London" value="<?php echo $city; ?>">
      <button type="submit" name="get_weather" class="btn">Get Weather</button>
    </div>
  </form>

  <?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
  <?php endif; ?>

  <?php if ($weatherData !== null): ?>
    <div class="weather-container">
      <div class="weather-info">
        <h2><?php echo $weatherData['name']; ?></h2>
        <p>Temperature: <?php echo round($weatherData['main']['temp'] - 273.15); ?>°C</p>
        <p>Humidity: <?php echo $weatherData['main']['humidity']; ?>%</p>
        <p>Weather: <?php echo $weatherData['weather'][0]['main']; ?></p>
      </div>

      <?php if (isset($imageUrl)): ?>
        <div class="image-container">
          <img src="<?php echo $imageUrl; ?>" alt="City Image" class="city-image">
        </div>
      <?php endif; ?>

      <!-- Bing Maps container -->
      <div class="map-container">
        <div id="bingMapContainer"></div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="footer">
  <a href="https://github.com/iznaor">
    <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
      <path d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3 .6 1.8.2 3.2.1 3.5 .6 .9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3 .5.4.9 1.2 .9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0" />
    </svg>
  </a>
  &copy; 2023 Ivan Znaor. All rights reserved.
</div>

</body>
</html>
<!-- Include the Bing Maps JavaScript SDK -->
<script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=<?php echo $bingMapsApiKey; ?>"></script>
<script type="text/javascript">
  function loadMap() {
    // Initialize the Bing Map
    var map = new Microsoft.Maps.Map('#bingMapContainer', {
      credentials: '<?php echo $bingMapsApiKey; ?>'
    });

    // Get the latitude and longitude of the chosen city
    var latitude = <?php echo $weatherData['coord']['lat']; ?>;
    var longitude = <?php echo $weatherData['coord']['lon']; ?>;

    // Create a location object for the city
    var location = new Microsoft.Maps.Location(latitude, longitude);

    // Add a pushpin to the map to mark the city location
    var pushpin = new Microsoft.Maps.Pushpin(location);
    map.entities.push(pushpin);

    // Set the map view to focus on the city location
    map.setView({ center: location, zoom: 10 });
  }

  // Load the map after the page has finished loading
  document.addEventListener('DOMContentLoaded', function() {
    loadMap();
  });
</script>