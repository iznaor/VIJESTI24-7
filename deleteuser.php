<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        // Include the database configuration file
        require_once 'config.php';

        // Prepare the SQL statement to delete the user account
        $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();

        // Check if the user account was successfully deleted
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = 'User account deleted successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to delete user account.';
        }

        $stmt->close();

        $mysqli->close();

        header('Location: admin.php');
        exit();
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Access denied.';
}
?>
