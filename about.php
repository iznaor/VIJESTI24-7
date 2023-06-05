<?php
session_start();
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

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
}

.content {
  max-width: 600px;
  text-align: center;
}

.content p {
  margin-bottom: 1.5rem;
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

.justified-text {
  text-align: justify;
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


@media (max-width: 768px) {
  .container {
    height: auto;
    padding: 20px;
  }

  .content {
    max-width: none;
    margin: 0;
  }
}
</style>
<script>
window.addEventListener('DOMContentLoaded', function() {
  var elements = document.getElementsByClassName('fade-in');
  var delay = 1000; 

  Array.prototype.forEach.call(elements, function(element, index) {
    setTimeout(function() {
      element.style.opacity = 1;
    }, index * delay);
  });

  var video = document.getElementById('video');
  setTimeout(function() {
    video.style.display = 'block';
  }, delay * elements.length);
});
</script>
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

<div class="container">
 <div class="content" style="margin-bottom: 0.5rem;">
  <div class="content">
    <h1 class="fade-in" style="opacity: 0; color: white; transition: opacity 0.5s; margin-top: -2rem;">About Us</h1>
    <h2 class="fade-in" style="opacity: 0; color: white; transition: opacity 0.5s; margin-top: -1rem;">Our Story</h2>
    <p class="fade-in justified-text" style="opacity: 0; color: white; transition: opacity 0.5s; margin-top: -1rem;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec arcu consequat, consectetur urna et, tristique nisi. Vestibulum eget lorem consectetur, placerat elit at, bibendum quam. Aliquam tincidunt massa vel lectus malesuada interdum. Proin laoreet eleifend consequat. Sed interdum ante ut aliquam tristique. Nulla facilisi. Mauris tristique, erat ac pharetra vulputate, tortor lectus convallis tellus, a tincidunt velit velit sed nunc. Donec malesuada, massa sit amet pellentesque viverra, mauris elit semper velit, id pellentesque tellus felis nec enim. Curabitur at lacinia mauris, ut molestie est. Fusce varius elit ut felis bibendum vulputate. Fusce non semper purus.</p>
    <p class="fade-in justified-text" style="opacity: 0; color: white; transition: opacity 0.5s; margin-bottom: -1rem;">Suspendisse ut lacinia nulla. Vivamus vel congue odio. Duis feugiat purus eu lacinia condimentum. Sed ultricies, odio a mattis efficitur, mi risus gravida tellus, vel sagittis neque orci sed leo. Donec dictum eleifend ultrices. Integer ut enim nec tellus laoreet interdum ut et elit. Integer eget vulputate ante. Donec aliquam sapien vel vehicula scelerisque. Etiam aliquet urna eu turpis ullamcorper efficitur. Aenean eleifend, quam non hendrerit ullamcorper, nisi felis sagittis nunc, id congue dolor nisi ut magna. <a href="https://www.pexels.com/video/working-station-853878/" style="color: gold; text-decoration: underline;">Link to video</a>.</p>

    <div style="display: flex; justify-content: center; margin-top: 2rem; position: relative;">
      <video id="video" class="fade-in" style="opacity: 0; display: none; width: 600px; height: 310px; transition: opacity 0.5s; position: absolute;" controls>
        <source src="/videos/office-6395.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
  </div>
 </div>
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

