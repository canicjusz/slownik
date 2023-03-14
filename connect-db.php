<?php
$filename = '.env';
if(file_exists($filename)){
  $env = parse_ini_file('$filename');
}
$mysqli = mysqli_connect("localhost", "root", $env['PASSWORD'] ?? '', "slownik");
$mysqli->set_charset('utf8mb4');
if ($mysqli->connect_error) {
  echo "Connection error:" . $mysqli->connect_error;
}

return $mysqli
  ?>