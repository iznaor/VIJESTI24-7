<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        // Include the database configuration file
        require_once 'config.php';

        // Prepare the SQL statement to remove admin privileges from the user
        $stmt = $mysqli->prepare("UPDATE users SET is_admin = 0 WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();

        // Check if the admin privileges were successfully removed
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = 'Admin privileges removed successfully.';
            $stmt->close();
            $mysqli->close();
            header('Location: admin.php');
            exit; 
        } else {
            echo 'Failed to remove admin privileges.';
        }

        $stmt->close();

        $mysqli->close();
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Access denied.';
}
?>


