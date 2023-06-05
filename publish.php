<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Include the database configuration file
        require_once 'config.php';

        // Retrieve the form data
        $title = $_POST['title'];
        $author = $_POST['author'];
        $date = $_POST['date'];
        $content = $_POST['content'];
        $flair = $_POST['flair'];

        // Check if an image file is uploaded
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = $_FILES['image']['name'];
            $image = 'uploads/' . $filename;

            // Move the uploaded file to the desired directory
            move_uploaded_file($tmp_name, $image);
        } else {
            // Set a default image if no file is uploaded
            $image = 'default.jpg';
        }

        // Prepare and execute the SQL statement to insert the news article
        $stmt = $mysqli->prepare("INSERT INTO news (author, publish_date, title, text, image, flair) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $author, $date, $title, $content, $image, $flair);
        $stmt->execute();

        $stmt->close();

        $mysqli->close();

        header("Location: news.php");
        exit();
    }
} else {
    // User does not have admin privileges
    echo 'Access denied.';
    exit();
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

.container {
  max-width: 500px;
  margin: 50px auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.container h2 {
  margin-bottom: 20px;
}

.container label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

.container input[type="text"],
.container input[type="date"],
.container textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.container textarea {
  height: 150px;
}

.container button[type="submit"] {
  background-color: #4CAF50;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.container button[type="submit"]:hover {
  background-color: #45a049;
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

<div class="container">
  <h2>Create News Article</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required>

    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="content">Content:</label>
    <textarea id="content" name="content" required></textarea>

    <label for="image">Image:</label>
    <input type="file" id="image" name="image" required>

    <label for="flair">Flair:</label>
    <input type="text" id="flair" name="flair" required>

    <button type="submit">Publish</button>
  </form>
</div>

<div class="footer">
  <a href="https://github.com/iznaor">
    <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
      <path d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3.6 1.8.2 3.2.1 3.5.6.9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3.5.4.9 1.2.9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0" />
    </svg>
  </a>
  &copy; 2023 Ivan Znaor. All rights reserved.
</div>

</body>
</html>
