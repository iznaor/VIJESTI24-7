<?php
// Include the config file
require_once 'config.php';

// Define variables and initialize with empty values
$firstname = $lastname = $dob = $email = $country = $password = $confirm_password = '';
$firstname_err = $lastname_err = $dob_err = $email_err = $country_err = $password_err = $confirm_password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate firstname
    if (empty(trim($_POST['firstname']))) {
        $firstname_err = 'Please enter your firstname.';
    } else {
        $firstname = trim($_POST['firstname']);
    }

    // Validate lastname
    if (empty(trim($_POST['lastname']))) {
        $lastname_err = 'Please enter your lastname.';
    } else {
        $lastname = trim($_POST['lastname']);
    }

    // Validate date of birth
    if (empty(trim($_POST['dob']))) {
        $dob_err = 'Please enter your date of birth.';
    } else {
        $dob = trim($_POST['dob']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email address.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate country
    if (empty(trim($_POST['country']))) {
        $country_err = 'Please select your country.';
    } else {
        $country = trim($_POST['country']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = 'Password must have at least 8 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm the password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password !== $confirm_password) {
            $confirm_password_err = 'Passwords do not match.';
        }
    }

    // Check input errors before inserting into database
    if (empty($firstname_err) && empty($lastname_err) && empty($dob_err) && empty($email_err) && empty($country_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = 'INSERT INTO users (firstname, lastname, dob, email, country, password) VALUES (?, ?, ?, ?, ?, ?)';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('ssssss', $param_firstname, $param_lastname, $param_dob, $param_email, $param_country, $param_password);

            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_dob = $dob;
            $param_email = $email;
            $param_country = $country;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 

            // Execute the prepared statement
            if ($stmt->execute()) {
                header('location: login.php');
                exit();
            } else {
                echo 'Something went wrong. Please try again later.';
            }

            $stmt->close();
        }
    }

    $mysqli->close();
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

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 280px);
            margin-top: 20px;
        }

        .register-form {
            max-width: 400px;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            max-height: calc(100% - 40px); 
            overflow-y: auto; 
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 3rem;
            font-style: italic;
            font-family: Arial, sans-serif;
            font-weight: bold;
            word-spacing: -0.9rem;
            margin: 0;
        }

        .logo span {
            color: white;
            font-size: 3rem;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .logo span.highlight {
            color: #ffd000;
        }

        .register-form {
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .register-form h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .register-form label {
            display: block;
            margin-bottom: 8px;
        }

        .register-form input[type="text"],
        .register-form input[type="email"],
        .register-form input[type="password"],
        .register-form input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .register-form span.error-message {
            color: red;
            font-size: 12px;
            margin-bottom: 8px;
            display: block;
        }

        .register-form input[type="submit"],
        .register-form input[type="reset"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
        }

        .register-form input[type="submit"]:hover,
        .register-form input[type="reset"]:hover {
            background-color: #ffd700;
            color: black;
        }

        .register-form p {
            margin-top: 20px;
            margin-bottom: 0;
        }

        .register-form p a {
            color: black;
            text-decoration: none;
        }

        .register-form p a:hover {
            text-decoration: underline;
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
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</div>

<div class="container">
    <div class="register-form">
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>First Name</label>
                <input type="text" name="firstname" value="<?php echo $firstname; ?>">
                <span><?php echo $firstname_err; ?></span>
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="lastname" value="<?php echo $lastname; ?>">
                <span><?php echo $lastname_err; ?></span>
            </div>
            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo $dob; ?>">
                <span><?php echo $dob_err; ?></span>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div>
            <div>
                <label>Country</label>
                <input type="text" name="country" value="<?php echo $country; ?>">
                <span><?php echo $country_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                <span><?php echo $confirm_password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Register">
                <input type="reset" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</div>

<div class="footer">
    <a href="https://github.com/iznaor">
        <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
            <path
                d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3.6 1.8.2 3.2.1 3.5.6.9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3.5.4.9 1.2.9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0"/>
        </svg>
    </a>
    &copy; 2023 Ivan Znaor. All rights reserved.
</div>

</body>
</html>
