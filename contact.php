<?php
session_start();

require_once 'config.php';

// Define a function to fetch the list of countries from an XML file
function getCountriesFromXML($file) {
  $xml = simplexml_load_file($file);
  $countries = [];
  foreach ($xml->country as $country) {
    $countries[] = (string) $country;
  }
  return $countries;
}

// Retrieve the list of countries from the local XML file
$countries = getCountriesFromXML($_SERVER['DOCUMENT_ROOT'] . '/ProjektZnaor/countries/countries.xml');


// Initialize variables
$firstName = $lastName = $email = $country = $message = '';

// Define error variables
$firstNameErr = $lastNameErr = $emailErr = $countryErr = $messageErr = '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate the form data
  if (empty($_POST['first_name'])) {
    $firstNameErr = 'First name is required';
  } else {
    $firstName = $_POST['first_name'];
  }

  if (empty($_POST['last_name'])) {
    $lastNameErr = 'Last name is required';
  } else {
    $lastName = $_POST['last_name'];
  }

  if (empty($_POST['email'])) {
    $emailErr = 'Email is required';
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $emailErr = 'Invalid email format';
  } else {
    $email = $_POST['email'];
  }

  if (empty($_POST['country'])) {
    $countryErr = 'Country is required';
  } else {
    $country = $_POST['country'];
  }

  if (empty($_POST['message'])) {
    $messageErr = 'Message is required';
  } else {
    $message = $_POST['message'];
  }

  // If there are no validation errors, insert the message into the database
  if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($countryErr) && empty($messageErr)) {
    $stmt = $mysqli->prepare("INSERT INTO messages (first_name, last_name, email, country, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $country, $message);
    $stmt->execute();
    $stmt->close();

    header("Location: contact.php");
    exit();
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
  justify-content: center;
  height: 50vh; 
}

.content label,
.content input,
.content select,
.content textarea {
  color: black;
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

form {
  display: grid;
  grid-gap: 20px;
  max-width: 400px;
  margin: 0 auto;
  background-color: white;
  color: black;
  padding: 20px;
}

.form-group {
  display: grid;
  grid-template-columns: 100px 1fr;
  align-items: center;
}

label {
  display: block;
  font-weight: bold;
}

input[type="text"],
input[type="email"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

textarea {
  height: 100px;
}

input[type="submit"] {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #45a049;
}

.error {
  color: red;
}

.container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.content {
  flex: 1;
  margin-right: 20px;
  max-width: 400px;
}

.map {
  flex: 1;
  display: flex;
  justify-content: flex-end;
  margin-top: 78px;
  margin-right: 750px;
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;
  }

  .map {
    margin-top: 20px;
    margin-right: 0;
  }

  .content,
  .map {
    max-width: none;
    margin-right: 0;
  }
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
  <div class="content">
    <h1 style="color: white;">Contact Us</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>">
        <span class="error"><?php echo $firstNameErr; ?></span>
      </div>
      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>">
        <span class="error"><?php echo $lastNameErr; ?></span>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <span class="error"><?php echo $emailErr; ?></span>
      </div>
      <div class="form-group">
        <label for="country">Country:</label>
        <select id="country" name="country">
          <option value="">Select a country</option>
          <?php foreach ($countries as $option) {
              echo '<option value="' . htmlspecialchars($option) . '"';
              if ($option === $country) {
                  echo ' selected';
              }
              echo '>' . htmlspecialchars($option) . '</option>';
          } ?>
        </select>
        <span class="error"><?php echo $countryErr; ?></span>
      </div>
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea id="message" name="message"><?php echo htmlspecialchars($message); ?></textarea>
        <span class="error"><?php echo $messageErr; ?></span>
      </div>
      <div>
        <input type="submit" value="Submit">
      </div>
    </form>
  </div>

  <div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.146468167255!2d15.971641316129023!3d45.80311367910698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d1431ec61c3b%3A0x36344c69c2d3329!2sZagreb%2C%20Croatia!5e0!3m2!1sen!2sus!4v1651361997324!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </div>
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
