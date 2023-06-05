<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    // Check if the message ID parameter exists
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // Include the database configuration file
        require_once 'config.php';

        // Prepare the delete statement
        $stmt = $mysqli->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);

        // Execute the delete statement
        if ($stmt->execute()) {
            echo 'Message deleted successfully.';
        } else {
            echo 'Error deleting the message.';
        }

        $stmt->close();

        $mysqli->close();
    } else {
        echo 'Invalid message ID.';
    }
} else {
    echo 'Access denied.';
}
?>
