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
  align-items: center;
}

.navbar ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex;
}

.navbar li {
  margin-right: 10px;
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

.content {
  background-color: white;
  padding: 20px;
  margin-top: 20px;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 10px;
  text-align: left;
}

.table th {
  background-color: #333;
  color: white;
}

.table td {
  background-color: #f2f2f2;
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

.show-if-logged-in {
  display: none;
}

.hide-if-logged-in {
  display: inline-block;
}

.show-if-admin {
  display: none;
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
    <li><a href="weather.php">Weather</a></li>
    <li><a href="movies.php">Movies</a></li>
    <li class="hide-if-logged-in"><a href="login.php">Login</a></li>
    <li class="hide-if-logged-in"><a href="register.php">Register</a></li>
    <li class="show-if-logged-in"><a href="profile.php">Profile</a></li>
    <li class="show-if-logged-in"><a href="logout.php">Logout</a></li>
    <li class="show-if-admin"><a href="admin.php">Admin</a></li>
    <li class="show-if-admin"><a href="publish.php">Publish</a></li>
  </ul>
</div>

<div class="content">
<?php
session_start();


// Check if a success message is set
if (isset($_SESSION['success_message'])) {
  echo '<div class="success-message" style="color: green;">' . $_SESSION['success_message'] . '</div>';

  // Remove the success message from the session
  unset($_SESSION['success_message']);
}

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    // Include the database configuration file
    require_once 'config.php';

    // Fetch all users from the database
    $stmt1 = $mysqli->prepare("SELECT id, firstname, lastname, dob, email, is_admin FROM users");
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    // Check if there are users in the database
    if ($result1->num_rows > 0) {
        echo '<h2>Users</h2>';
        echo '<table class="table">';
        echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Email</th><th>Actions</th></tr>';

        // Loop through each user
        while ($row = $result1->fetch_assoc()) {
            $userID = $row['id'];
            $firstName = $row['firstname'];
            $lastName = $row['lastname'];
            $dateOfBirth = $row['dob'];
            $email = $row['email'];

            // Display user information in a table row
            echo '<tr>';
            echo '<td>' . $userID . '</td>';
            echo '<td>' . $firstName . '</td>';
            echo '<td>' . $lastName . '</td>';
            echo '<td>' . $dateOfBirth . '</td>';
            echo '<td>' . $email . '</td>';
            echo '<td>';
            if ($userID !== $_SESSION['id']) {
                echo '<a href="deleteuser.php?id=' . $userID . '">Delete</a> ';
                if ($_SESSION['is_admin'] === 1) {
                    if ($row['is_admin'] === 1) {
                        echo '<a href="removeadmin.php?id=' . $userID . '">Remove Admin</a> ';
                    } else {
                        echo '<a href="makeadmin.php?id=' . $userID . '">Make Admin</a> ';
                    }
                }
            }
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No users found.';
    }

    $stmt1->close();

    // Fetch all news articles from the database
    $stmt = $mysqli->prepare("SELECT id, author, publish_date, title FROM news");
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are news articles in the database
    if ($result->num_rows > 0) {
        echo '<h2>News</h2>';
        echo '<table class="table">';
        echo '<tr><th>ID</th><th>Author</th><th>Publish Date</th><th>Title</th><th>Actions</th></tr>';

        // Loop through each news article
        while ($row = $result->fetch_assoc()) {
            $newsID = $row['id'];
            $author = $row['author'];
            $publishDate = $row['publish_date'];
            $title = $row['title'];

            // Display news article in a table row
            echo '<tr>';
            echo '<td>' . $newsID . '</td>';
            echo '<td>' . $author . '</td>';
            echo '<td>' . $publishDate . '</td>';
            echo '<td>' . $title . '</td>';
            echo '<td>';
            echo '<a href="deletenews.php?id=' . $newsID . '">Delete</a> ';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No news articles found.';
    }

    // Fetch all messages from the database
    $stmt2 = $mysqli->prepare("SELECT id, first_name, last_name, email, country, message, created_at FROM messages");
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    // Check if there are messages in the database
    if ($result2->num_rows > 0) {
        echo '<h2>Messages</h2>';
        echo '<table class="table">';
        echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Country</th><th>Message</th><th>Created At</th><th>Actions</th></tr>';

        // Loop through each message
        while ($row = $result2->fetch_assoc()) {
            $messageID = $row['id'];
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $email = $row['email'];
            $country = $row['country'];
            $message = $row['message'];
            $createdAt = $row['created_at'];

            // Display message information in a table row
            echo '<tr>';
            echo '<td>' . $messageID . '</td>';
            echo '<td>' . $firstName . '</td>';
            echo '<td>' . $lastName . '</td>';
            echo '<td>' . $email . '</td>';
            echo '<td>' . $country . '</td>';
            echo '<td>' . $message . '</td>';
            echo '<td>' . $createdAt . '</td>';
            echo '<td><a href="deletemessage.php?id=' . $messageID . '">Delete</a></td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No messages found.';
    }

    $stmt2->close();

    $mysqli->close();
} else {
    echo 'Access denied.';
}
?>

</div>

<div class="footer">
  <a href="https://github.com/iznaor">
    <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
      <path d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3.6 1.8.2 3.2.1 3.5.6.9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3.5.4.9 1.2.9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0" />
    </svg>
  </a>
  &copy; 2023 Ivan Znaor. All rights reserved.
</div>

<script>
  if (<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>) {
    document.querySelectorAll('.show-if-logged-in').forEach(function(element) {
      element.style.display = 'inline-block';
    });
    document.querySelectorAll('.hide-if-logged-in').forEach(function(element) {
      element.style.display = 'none';
    });
  }

  if (<?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1 ? 'true' : 'false'; ?>) {
    document.querySelectorAll('.show-if-admin').forEach(function(element) {
      element.style.display = 'inline-block';
    });
  }
</script>

<script>
  setTimeout(function() {
    var successMessage = document.querySelector('.success-message');
    if (successMessage) {
      successMessage.style.display = 'none';
    }
  }, 5000);
</script>

</body>
</html>
