<?php
session_start();

include 'config.php';

// Function to retrieve the latest news from the database
function getLatestNews() {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to retrieve the latest news
    $query = "SELECT * FROM news ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    // Fetch the news data
    $news = mysqli_fetch_assoc($result);

    // Close the database connection
    mysqli_close($conn);

    return $news;
}

$latestNews = getLatestNews();
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
  display: flex; 
  justify-content: space-between; 
}

.navbar ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex; 
  align-items: center; 
}

.navbar li {
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

@keyframes fallAnimation {
  0% {
    transform: translateY(-100%);
  }
  100% {
    transform: translateY(0);
  }
}

.animation-container {
  display: flex;
  margin: 0.1rem;
  justify-content: center;
  animation: fallAnimation 1s forwards;
}

.animation-container span {
  animation: fallAnimation 1s forwards;
  animation-delay: calc(0.2s * var(--delay));
}

.content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 70vh;
    text-align: center;
    margin-top: 20vh;
}

.css-typing {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}


.css-typing p {
  border-right: .15em solid gold;
  font-family: "Courier";
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
}
.css-typing p:nth-child(1) {
  width: 18.3em;
  -webkit-animation: type 2s steps(40, end);
  animation: type 2s steps(40, end);
  -webkit-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
}

.css-typing p:nth-child(2) {
  width: 5.5em;
  opacity: 0;
  -webkit-animation: type2 2s steps(40, end);
  animation: type2 2s steps(40, end);
  -webkit-animation-delay: 2s;
  animation-delay: 2s;
  -webkit-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
}

.css-typing p:nth-child(3) {
  width: 8.3em;
  opacity: 0;
  -webkit-animation: type3 2s steps(40, end), blink .5s step-end infinite alternate;
  animation: type3 2s steps(40, end), blink .2s step-end infinite alternate;
  -webkit-animation-delay: 4s;
  animation-delay: 4s;
  -webkit-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
}

.news-preview {
  color: white;
}

@keyframes type {
  0% {
    width: 0;
  }
  99.9% {
    border-right: .15em solid gold;
  }
  100% {
    border: none;
  }
}

@-webkit-keyframes type {
  0% {
    width: 0;
  }
  99.9% {
    border-right: .15em solid gold;
  }
  100% {
    border: none;
  }
}

@keyframes type2 {
  0% {
    width: 0;
  }
  1% {
    opacity: 1;
  }
  99.9% {
    border-right: .15em solid gold;
  }
  100% {
    opacity: 1;
    border: none;
  }
}

@-webkit-keyframes type2 {
  0% {
    width: 0;
  }
  1% {
    opacity: 1;
  }
  99.9% {
    border-right: .15em solid gold;
  }
  100% {
    opacity: 1;
    border: none;
  }
}

@keyframes type3 {
  0% {
    width: 0;
  }
  1% {
    opacity: 1;
  }
  100% {
    opacity: 1;
  }
}

@-webkit-keyframes type3 {
  0% {
    width: 0;
  }
  1% {
    opacity: 1;
  }
  100% {
    opacity: 1;
  }
}

@keyframes blink {
  50% {
    border-color: transparent;
  }
}
@-webkit-keyframes blink {
  50% {
    border-color: tranparent;
  }
}
</style>
</head>
<body>
<div class="animation-container">
  <h1 style="font-size: 3rem; font-style: italic; font-family: Arial, sans-serif; font-weight: bold; word-spacing: -0.9rem;">
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 1;">V</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 2;">I</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 3;">J</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 4;">E</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 5;">S</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 6;">T</span>
    <span style="color: white; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 7;">I</span>
    <span style="color: gold; font-size: 3rem; font-family: Arial, sans-serif; font-weight: bold; --delay: 8;">24/7</span>
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

<div class="content" id="news-content" style="display: none;">
  <h2 style="color: white;">
    <?php echo 'Najnovije'; ?>
  </h2>
  <h2 style="color: white;">
    👉 <?php echo $latestNews['title']; ?>
  </h2>
  <p class="news-preview"><?php echo substr($latestNews['text'], 0, 100) . '...'; ?></p>
  <a href="news.php">Read More</a>
</div>







<script>
  function showLatestNews() {
    var titleElement = document.getElementById('news-title');
    var textElement = document.getElementById('news-text');

    titleElement.style.display = 'block';
    textElement.style.display = 'block';
  }

  setTimeout(showLatestNews, 12000);
</script>

<div class="css-typing">
  <p style="font-size: 2rem; color: white;">Najnovije vijesti iz politike</p>
  <p style="font-size: 2rem; color: white;">- sporta</p>
  <p style="font-size: 2rem; color: white;">- tehnologije</p>
</div>


<div class="footer">
  <a href="https://github.com/iznaor">
    <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
      <path d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3 .6 1.8 .2 3.2 .1 3.5 .6 .9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3 .5 .4 .9 1.2 .9 2.4v3.5c0 .4 .2 .8 .8 .6A12 12 0 0 0 12 0" />
    </svg>
  </a>
  &copy; 2023 Ivan Znaor. All rights reserved.
</div>

<script>
  function hideElement() {
    var element = document.querySelector('.css-typing');
    element.style.display = 'none';
  }

  setTimeout(hideElement, 8000);
</script>

<script>
  function showLatestNews() {
    var newsContent = document.getElementById('news-content');

    newsContent.style.display = 'block';
  }

  setTimeout(showLatestNews, 9000);
</script>


</body>
</html>
