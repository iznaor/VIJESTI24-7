<?php
session_start();

// Check if the user is logged in and has admin privileges
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
    // Check if the news article ID is provided in the URL
    if (isset($_GET['id'])) {
        $newsID = $_GET['id'];

        require_once 'config.php';

        // Delete the news article from the database
        $stmt = $mysqli->prepare("DELETE FROM news WHERE id = ?");
        $stmt->bind_param("i", $newsID);
        $stmt->execute();
        $stmt->close();

        header("Location: admin.php");
        exit();
    } else {
        echo 'News article ID not provided.';
    }
} else {
    echo 'Access denied.';
}
?>
