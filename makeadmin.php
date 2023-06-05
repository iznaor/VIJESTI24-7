<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        // Include the database configuration file
        require_once 'config.php';

        // Prepare the SQL statement to update the user as an admin
        $stmt = $mysqli->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();

        // Check if the user was successfully updated as an admin
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = 'User successfully made admin.';
        } else {
            $_SESSION['error_message'] = 'Failed to make user admin.';
        }

        $stmt->close();

        $mysqli->close();

        header("Location: admin.php");
        exit();
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Access denied.';
}
?>
