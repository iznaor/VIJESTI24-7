<?php
session_start();

include 'config.php';

$apiKey = '1e071416167d9c0077aeca62877f7212';

// Initialize variables
$genres = array();
$actors = array();
$error = '';
$movies = array();

// Fetch the list of genres from the API
$genresUrl = "https://api.themoviedb.org/3/genre/movie/list?api_key=$apiKey";
$genresResponse = file_get_contents($genresUrl);
$genresData = json_decode($genresResponse, true);
if (isset($genresData['genres'])) {
    $genres = $genresData['genres'];
} else {
    $error = 'Failed to fetch genre data.';
}

// Fetch the list of actors from the API
$actorsUrl = "https://api.themoviedb.org/3/person/popular?api_key=$apiKey";
$actorsResponse = file_get_contents($actorsUrl);
$actorsData = json_decode($actorsResponse, true);
if (isset($actorsData['results'])) {
    $actors = $actorsData['results'];
} else {
    $error = 'Failed to fetch actor data.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $genreId = $_POST['genre'];
    $actorName = $_POST['actor']; // Updated variable name
    $releaseYear = $_POST['release_year'];

    // Prepare the API URL based on the user's selection
    $apiUrl = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey";
    $queryParameters = array();

    if (!empty($genreId) && $genreId !== 'all') {
        $queryParameters['with_genres'] = $genreId;
    }

    if (!empty($actorName)) {
        // Search for actor ID based on the actor name
        $actorSearchUrl = "https://api.themoviedb.org/3/search/person?api_key=$apiKey&query=" . urlencode($actorName);
        $actorSearchResponse = file_get_contents($actorSearchUrl);
        $actorSearchData = json_decode($actorSearchResponse, true);

        if (isset($actorSearchData['results'][0]['id'])) {
            $actorId = $actorSearchData['results'][0]['id'];
            $queryParameters['with_cast'] = $actorId;
        } else {
            $error = 'No matching actor found.';
        }
    }

    if (!empty($releaseYear)) {
        $queryParameters['primary_release_year'] = $releaseYear;
    }

    if (!empty($queryParameters)) {
        $apiUrl .= '&' . http_build_query($queryParameters);
    }

    // Make a GET request to the API
    $response = file_get_contents($apiUrl);
    $moviesData = json_decode($response, true);

    // Check if the request was successful
    if (isset($moviesData['results'])) {
        $movies = $moviesData['results'];
    } else {
        $error = 'Failed to fetch movie data.';
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

.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  color: white;
}

.container h1 {
  font-size: 24px;
  margin-bottom: 20px;
}

.form-field {
  margin-bottom: 10px;
}

.form-field label {
  display: block;
  font-size: 16px;
  margin-bottom: 5px;
}

.form-field select, .form-field input {
  padding: 5px;
  font-size: 14px;
  border: none;
  border-radius: 4px;
}

.form-field select {
  width: 100%;
}

.form-field input[type="number"] {
  width: 100px;
}

.form-field button {
  background-color: #4CAF50;
  color: white;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.form-field button:hover {
  background-color: #45a049;
}

.movie {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  padding: 20px;
  margin-bottom: 20px;
}

.movie h2 {
  font-size: 20px;
  margin-bottom: 10px;
  color:black;
}

.movie p {
  margin-bottom: 10px;
  color:black;
}

.movie img {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
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
  <div class="container">
    <h1>Movies</h1>

    <form method="post" class="form-field">
        <label for="genre">Genre:</label>
        <select id="genre" name="genre">
            <option value="all">All Genres</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo $genre['id']; ?>"><?php echo $genre['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="actor" style="color: white;">Actor:</label>
        <input type="text" id="actor" name="actor" placeholder="Enter actor's name">

        <label for="release_year">Release Year:</label>
        <input type="number" id="release_year" name="release_year">

        <button type="submit">Search Movies</button>
    </form>

    <?php if (!empty($error)): ?>
        <p style="color: white;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php foreach ($movies as $movie): ?>
        <div class="movie">
            <h2><?php echo $movie['title']; ?></h2>
            <p><?php echo $movie['overview']; ?></p>
            <?php if (!empty($movie['poster_path'])): ?>
                <img src="https://image.tmdb.org/t/p/w200/<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?> Poster">
            <?php endif; ?>
            <?php
            // Get the movie details from the API
            $movieDetailsUrl = "https://api.themoviedb.org/3/movie/" . $movie['id'] . "?api_key=$apiKey";
            $movieDetailsResponse = file_get_contents($movieDetailsUrl);
            $movieDetails = json_decode($movieDetailsResponse, true);

            if (isset($movieDetails['vote_average'])) {
                $averageScore = $movieDetails['vote_average'];
                echo '<p><strong><em>Average Score: ' . $averageScore . '</em></strong></p>';
            }
            ?>
        </div>
    <?php endforeach; ?>
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
