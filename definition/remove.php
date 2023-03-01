<?php
require_once('../connect-db.php');
require_once('../helpers.php');
session_start();
$query_id = $_GET["id"];
if (!isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header("Location: index.php?id=$query_id");
  exit;
}
$definition_query = "SELECT author_id FROM definition WHERE id = '$query_id'";
if (!$definition_result = $mysqli->query($definition_query)) {
  echo $mysqli->error;
}
$author_id = $definition_result->fetch_object()->author_id;
if ($author_id == $_SESSION['id'] || check_adminship()) {
  $remove_query = "DELETE FROM definition WHERE id='$query_id'";
  if (!$remove_result = $mysqli->query($remove_query)) {
    echo $mysqli->error;
  }
}
header("Location: index.php?id=$query_id");
?>