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
    background: linear-gradient(to right, #0f2027, #203a43, #2c5364);   }

  .content {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .news-container {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
    max-width: 600px;
  }

  .news-article {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  .news-article img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
  }

  .news-details h2 {
    font-size: 24px;
    margin: 0;
  }

  .news-details p {
    margin: 5px 0;
  }

  label {
    font-weight: bold;
  }

  input[type="text"],
  textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  button {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  button:hover {
    background-color: #45a049;
  }


</style>
</head>
<body>
<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['is_admin'] === 1) {
  // Check if the news_id parameter is set in the URL
  if (isset($_GET['news_id'])) {
    $newsID = $_GET['news_id'];

    // Retrieve the news article from the database
    $stmt = $mysqli->prepare("SELECT id, author, publish_date, title, text, image, flair FROM news WHERE id = ?");
    $stmt->bind_param("i", $newsID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $title = $row['title'];
      $text = $row['text'];

      // Output the HTML
      echo '<div class="content">';
      echo '<div class="news-container">';
      echo '<form method="POST" action="updatenews.php">';
      echo '<input type="hidden" name="news_id" value="' . $newsID . '">';

      echo '<div class="news-article">';
      echo '<img src="' . $row['image'] . '" alt="Article Image">';
      echo '<div class="news-details">';
      echo '<h2>' . $title . '</h2>';
      echo '<p>Author: ' . $row['author'] . '</p>';
      echo '<p>Publish Date: ' . $row['publish_date'] . '</p>';
      echo '</div>';
      echo '</div>';

      echo '<label for="title">Title:</label><br>';
      echo '<input type="text" id="title" name="title" value="' . $title . '"><br>';
      echo '<label for="text">Text:</label><br>';
      echo '<textarea id="text" name="text">' . $text . '</textarea><br>';
      echo '<label for="flair">Flair:</label><br>';
      echo '<input type="text" id="flair" name="flair" value="' . $row['flair'] . '"><br>';
      echo '<button type="submit">Update</button>';

      echo '</form>';
      echo '</div>';
      echo '</div>';

      echo '</body>';
      echo '</html>';
    } else {
      echo 'News article not found.';
    }

    $stmt->close();
  } else {
    echo 'Missing news_id parameter.';
  }
} else {
  echo 'You do not have permission to edit news articles.';
}
?>
</body>
</html>
