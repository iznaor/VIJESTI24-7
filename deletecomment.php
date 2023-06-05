<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
  $commentID = $_POST['comment_id'];

  // Check if the logged-in user is the author of the comment
  $checkStmt = $mysqli->prepare("SELECT email FROM comments WHERE id = ?");
  $checkStmt->bind_param("i", $commentID);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  if ($checkResult->num_rows === 1) {
    $commentRow = $checkResult->fetch_assoc();
    $userEmail = $commentRow['email'];

    if ($_SESSION['email'] === $userEmail) {
      $deleteStmt = $mysqli->prepare("DELETE FROM comments WHERE id = ?");
      $deleteStmt->bind_param("i", $commentID);
      $deleteStmt->execute();
      $deleteStmt->close();

      header("Location: news.php");
      exit();
    }
  }
}

header("Location: news.php");
exit();
?>
