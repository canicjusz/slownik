<?php
require_once(__DIR__ . '/helpers.php');

$filename = __DIR__ . '/.env';
if (file_exists($filename)) {
  $env = parse_ini_file($filename);
}
class CustomSql extends mysqli
{
  public function __construct($host, $user, $password, $database)
  {
    parent::__construct($host, $user, $password, $database);
  }

  public function execute_query($query, ?array $params = null): mysqli_result|bool
  {
    $statement = $this->prepare($query);
    if ($params) {
      $parameters_length = count($params);
      $statement->bind_param(str_repeat('s', $parameters_length), ...$params);
    }
    $statement->execute();
    if ($statement->insert_id) {
      return $statement->insert_id;
    } else if ($result = $statement->get_result()) {
      return $result;
    } else {
      return !$statement->errno;
    }
  }
}

$mysqli = new CustomSql("localhost", "root", $env['PASSWORD'] ?? '', "slownik");
$mysqli->set_charset('utf8mb4');
if ($mysqli->connect_error) {
  echo "Connection error:" . $mysqli->connect_error;
}

return $mysqli
  ?>