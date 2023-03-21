<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/connect-db.php');
session_start();
$query_id = $_GET["id"];
$user_id = $_SESSION['id'];
if (!isset($user_id, $_SESSION['name'], $_SESSION['avatar'])) {
  header("Location: {$_SESSION['uri']}");
  exit;
}
$query = "REPLACE INTO ratio(user_id, definition_id, opinion) VALUES(?, ?, 
CASE 
	WHEN EXISTS(SELECT opinion FROM (SELECT * FROM ratio) as x WHERE user_id=? AND definition_id=? AND opinion='1') THEN '0'
	ELSE '1'
END);";
if ($mysqli->execute_query($query, [$user_id, $query_id, $user_id, $query_id])) {
  header("Location: {$_SESSION['uri']}");
  exit;
}
echo $mysqli->error;
?>