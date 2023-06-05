<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
  // Check if the form data is submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['news_id'])) {
    $newsID = $_POST['news_id'];
    $title = $_POST['title'];
    $text = $_POST['text'];

    // Update the news article in the database
    $stmt = $mysqli->prepare("UPDATE news SET title = ?, text = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $text, $newsID);
    $stmt->execute();
    $stmt->close();

    // Redirect to the news page after the update
    header("Location: news.php");
    exit();
  } else {
    echo 'Invalid request.';
  }
} else {
  echo 'You do not have permission to update news articles.';
}
?>
