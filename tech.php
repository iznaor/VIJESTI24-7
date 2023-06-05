<?php
session_start();
require_once 'config.php';
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

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  margin-top: 10px;
  margin-bottom: 40px;

}

.news-article {
  max-width: 600px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  padding: 20px;
  margin-bottom: 20px;
}

.news-article h2 {
  font-size: 24px;
  margin-bottom: 10px;
}

.news-article p {
  margin-bottom: 10px;
}

.news-article img {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.pagination a {
  display: inline-block;
  margin: 0 5px;
  padding: 5px 10px;
  background-color: gold;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
}

.pagination a.active {
  background-color: green;
}

.search-form {
  margin-bottom: 20px;
}

.search-form input[type="text"] {
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.search-form button {
  background-color: #4CAF50;
  color: #fff;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.search-form button:hover {
  background-color: #45a049;
}

.comment {
  margin-bottom: 5px;
  padding-bottom: 5px;
  background-color: transparent; 
  color:white;
}

.comment-form {
  margin-top: 5px;
  margin-bottom: 45px;
  padding-top: 5px;
  width: 100%; 
  max-width: 600px; 
  box-sizing: border-box; 
}

.comment-form h3 {
  margin-bottom: 10px;
}

.comment-form textarea {
  width: 100%;
  height: 100px;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.comment-form button {
  background-color: #4CAF50;
  color: #fff;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.comment-form button:hover {
  background-color: #45a049;
}

.comments {
  margin-top: 5px;
  width: 600px;;
  padding-top: 20px;
  max-height: 400px;
  overflow-y: auto;
}

.comments-container {
  margin-bottom: 10px;
  padding-bottom: 10px;
}

.comment {
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #ccc;
}

.comment p {
  margin-bottom: 5px;
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
  <?php
  // Query to fetch news articles with flair starting with 't'
  $sql = "SELECT * FROM news WHERE flair LIKE 't%'";
  $result = $mysqli->query($sql);

  // Display the news articles
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $newsID = $row['id'];
      $author = $row['author'];
      $publishDate = $row['publish_date'];
      $title = $row['title'];
      $image = $row['image'];
      $text = $row['text'];
      $flair = $row['flair'];
  
      // Display news article
      echo '<div class="news-article">';
      echo '<h2>' . $title . '</h2>';
      echo '<p>Published by: ' . $author . ' on ' . $publishDate . '</p>';
      echo '<img src="' . $image . '" alt="' . $title . '">';
      echo '<p>' . $text . '</p>';
      echo '<p>Flair: ' . $flair . '</p>';
      echo '</div>';
    }
  } else {
    echo 'No news articles found.';
  }

  $mysqli->close();
  ?>
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




