<?php
$env = parse_ini_file('.env');
$mysqli = mysqli_connect("localhost", "root", $env['PASSWORD'] ?? '', "slownik");
$mysqli->set_charset('utf8mb4');
if ($mysqli->connect_error) {
  echo "Connection error:" . $mysqli->connect_error;
}

return $mysqli
  ?>