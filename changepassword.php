<?php
// Start the session and check if the user is logged in
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate the form data
        if (isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
            // Sanitize and store the form data
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];

            // Validate the current password and perform the password change
            require_once 'config.php';

            // Prepare the SQL statement to fetch the user's current password
            $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the current password
            if (password_verify($currentPassword, $hashedPassword)) {
                // Generate the new hashed password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Free the result set
                $stmt->free_result();

                // Prepare the SQL statement to update the user's password
                $updateStmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $newHashedPassword, $_SESSION['id']);
                $updateStmt->execute();

                // Check if the password was successfully updated
                if ($updateStmt->affected_rows > 0) {
                    $_SESSION['passwordChanged'] = true; // Set a session variable to indicate successful password change
                    header("Location: profile.php");
                } else {
                    $_SESSION['passwordChanged'] = false; // Set a session variable to indicate failed password change
                    header("Location: profile.php");
                }

                $updateStmt->close();
            } else {
                echo 'Incorrect current password.';
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo 'Invalid form data.';
        }
    } else {
        echo 'Invalid request method.';
    }
} else {
    echo 'Please log in to change your password.';
}
?>
