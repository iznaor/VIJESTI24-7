<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Include the database configuration file
    require_once 'config.php';

    // Prepare the SQL statement to fetch user data
    $stmt = $mysqli->prepare("SELECT firstname, lastname, dob, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists in the database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstName = $row['firstname'];
        $lastName = $row['lastname'];
        $dateOfBirth = $row['dob'];
        $email = $row['email'];
    } else {
        echo 'User not found.';
    }

    $stmt->close();

    // Fetch the number of comments made by the user
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS commentCount FROM comments WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result) {
        $row = $result->fetch_assoc();
        $commentCount = $row['commentCount'];
    } else {
        $commentCount = 0;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $mysqli->close();
} else {
    echo 'Please log in to view your profile.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        setTimeout(function () {
            var successMessage = document.getElementById('success-message');
            var errorMessage = document.getElementById('error-message');
            if (successMessage) {
                successMessage.remove();
            }
            if (errorMessage) {
                errorMessage.remove();
            }
        }, 5000); 
    </script>

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

        .profile {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            color: #333;
        }

        .profile h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .profile p {
            margin: 10px 0;
        }

        .profile label {
            display: block;
            font-weight: bold;
        }

        .profile .form-group {
            margin-bottom: 20px;
        }

        .profile .form-group input[type="text"],
        .profile .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .profile .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
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
            echo '<li><a href="weather.php">Weather</a></li>';
            echo '<li><a href="movies.php">Movies</a></li>';
            echo '<li><a href="profile.php">Profile</a></li>';
            echo '<li><a href="logout.php">Logout</a></li>';
            if ($_SESSION['is_admin'] === 1) {
                echo '<li><a href="admin.php">Admin</a></li>';
                echo '<li><a href="publish.php">Publish</a></li>';
            }
        } else {
            echo '<li><a href="login.php">Login</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
        }
        ?>
    </ul>
</div>

<div class="content">
    <div class="profile">
        <h2>Profile</h2>

        <?php
        // Check if the user is logged in
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            // Display user data
            echo '<div class="form-group">';
            echo '<label for="firstName">First Name:</label>';
            echo '<p id="firstName">' . $firstName . '</p>';
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label for="lastName">Last Name:</label>';
            echo '<p id="lastName">' . $lastName . '</p>';
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label for="dateOfBirth">Date of Birth:</label>';
            echo '<p id="dateOfBirth">' . $dateOfBirth . '</p>';
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label for="email">Email:</label>';
            echo '<p id="email">' . $email . '</p>';
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label for="commentCount">Number of Comments:</label>';
            echo '<p id="commentCount">' . $commentCount . '</p>';
            echo '</div>';

            // Allow the user to change their password
            echo '<h3>Change Password</h3>';
            echo '<form action="changepassword.php" method="post">';
            echo '<div class="form-group">';
            echo '<label for="currentPassword">Current Password:</label>';
            echo '<input type="password" id="currentPassword" name="currentPassword" required>';
            echo '<input type="checkbox" id="showCurrentPassword"> Show Password';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="newPassword">New Password:</label>';
            echo '<input type="password" id="newPassword" name="newPassword" required>';
            echo '<input type="checkbox" id="showNewPassword"> Show Password';
            echo '</div>';
            echo '<input type="submit" value="Change Password">';
            echo '</form>';
        } else {
            echo 'Please log in to view your profile.';
        }
        ?>
        <script>
            var showCurrentPasswordCheckbox = document.getElementById('showCurrentPassword');
            var currentPasswordInput = document.getElementById('currentPassword');
            
            showCurrentPasswordCheckbox.addEventListener('change', function () {
                if (showCurrentPasswordCheckbox.checked) {
                currentPasswordInput.type = 'text';
                } else {
                currentPasswordInput.type = 'password';
                }
            });
            
            var showNewPasswordCheckbox = document.getElementById('showNewPassword');
            var newPasswordInput = document.getElementById('newPassword');
            
            showNewPasswordCheckbox.addEventListener('change', function () {
                if (showNewPasswordCheckbox.checked) {
                newPasswordInput.type = 'text';
                } else {
                newPasswordInput.type = 'password';
                }
            });
        </script>
        <?php
            if (isset($_SESSION['passwordChanged'])) {
                if ($_SESSION['passwordChanged'] === true) {
                    echo '<p id="success-message">Password changed successfully.</p>';
                } elseif ($_SESSION['passwordChanged'] === false) {
                    echo '<p id="error-message">Failed to change password.</p>';
                }
                unset($_SESSION['passwordChanged']); 
            }
        ?>
    </div>
</div>


<div class="footer">
    <a href="https://github.com/iznaor">
        <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
            <path
                d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3.6 1.8.2 3.2.1 3.5.6.9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3 .5.4.9 1.2 .9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0" />
        </svg>
    </a>
    &copy; 2023 Ivan Znaor. All rights reserved.
</div>
</body>
</html>
