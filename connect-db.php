<?php
$mysqli = mysqli_connect("localhost", "root", "Canicjusz2004", "slownik");
$mysqli->set_charset('utf8mb4');
if ($mysqli->connect_error) {
  echo "Connection error:" . $mysqli->connect_error;
}

return $mysqli
  ?>