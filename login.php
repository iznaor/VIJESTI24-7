<?php
session_start();

require_once 'config.php';

// Check if the user is already logged in, if yes then redirect to profile page
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: profile.php');
    exit();
}

// Define variables and initialize with empty values
$email = $password = '';
$email_err = $password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email address.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Check input errors before validating credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = 'SELECT id, email, password, is_admin FROM users WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows === 1) {
                    // Bind the result variables
                    $stmt->bind_result($id, $email, $hashed_password, $is_admin);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['email'] = $email;
                            $_SESSION['is_admin'] = $is_admin;

                            // Redirect to profile page
                            header('location: profile.php');

                            // Display success message
                            $_SESSION['success_msg'] = 'Successfully logged in.';
                            exit();
                        } else {
                            // Password is not valid, display a generic error message
                            $password_err = 'Invalid email or password.';
                        }
                    }
                } else {
                    // Email doesn't exist, display a generic error message
                    $email_err = 'Invalid email or password.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
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
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 520px);
            margin-top: 20px;
            margin-bottom: 20px;
        }


        .login-form {
            max-width: 400px;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            margin-bottom: 20px;
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

        .login-form h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .login-form label {
            display: block;
            margin-bottom: 8px;
        }

        .login-form input[type="email"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .login-form span.error-message {
            color: red;
            font-size: 12px;
            margin-bottom: 8px;
            display: block;
        }

        .login-form input[type="submit"],
        .login-form input[type="reset"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
        }

        .login-form input[type="submit"]:hover,
        .login-form input[type="reset"]:hover {
            background-color: #45a049;
        }

        .login-form p {
            margin-top: 20px;
            margin-bottom: 0;
        }

        .login-form p a {
            color: black;
            text-decoration: none;
        }

        .login-form p a:hover {
            text-decoration: underline;
        }
        
        @media screen and (max-width: 768px) {
            .container {
                padding: 0 10px; 
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 0 5px; 
            }

            .login-form {
                padding: 10px; 
            }
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
            echo '<li><a href="weather.php">Weather</a></li>';
            echo '<li><a href="movies.php">Movies</a></li>';
            echo '<li><a href="profile.php">Profile</a></li>';
            if ($_SESSION['is_admin'] === 1) {
                echo '<li><a href="admin.php">Admin</a></li>';
            }
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            echo '<li><a href="login.php">Login</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
        }
        ?>
    </ul>
</div>

<div class="container">
    <div class="login-form">
        <h2>Login</h2>
        <?php
        if (isset($_SESSION['success_msg'])) {
            echo '<p class="success-message">' . $_SESSION['success_msg'] . '</p>';
            unset($_SESSION['success_msg']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <span class="error-message"><?php echo $email_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span class="error-message"><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Login">
                <input type="reset" value="Reset">
            </div>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</div>

<script>
    const navbar = document.querySelector('.navbar');
    const navbarHeight = navbar.offsetHeight;

    const container = document.querySelector('.container');
    container.style.top = `calc(50% + ${navbarHeight / 2}px)`;
</script>

<div class="footer">
    <a href="https://github.com/iznaor">
        <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24" height="24">
            <path
                d="M12 0a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.6-4-1.6-.5-1.4-1.2-1.8-1.2-1.8-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1.9 1.5 2.3 1.1 2.9.9.1-.7.4-1.1.8-1.4-2.9-.3-6-1.4-6-6.3 0-1.4.5-2.6 1.1-3.6-.1-.3-.5-1.7.1-3.5 0 0 1.1-.4 3.6 1.3a12.3 12.3 0 0 1 3.2-.4c1.1 0 2.2.1 3.2.4 2.5-1.7 3.6-1.3 3.6-1.3.6 1.8.2 3.2.1 3.5.6.9 1.1 2.1 1.1 3.6 0 4.9-3.1 6-6.1 6.3.5.4.9 1.2.9 2.4v3.5c0 .4.2.8.8.6A12 12 0 0 0 12 0" />
        </svg>
    </a>
    &copy; 2023 Ivan Znaor. All rights reserved.
</div>

</body>
</html>
