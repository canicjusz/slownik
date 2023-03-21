<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/helpers.php');

$filename = commencing_path() . '.env';
if (file_exists($filename)) {
  $env = parse_ini_file($filename);
}
class CustomSql extends mysqli
{
  public function __construct($host, $user, $password, $database)
  {
    parent::__construct($host, $user, $password, $database);
  }

  public function execute_query($query, $parameters = [])
  {
    $statement = $this->prepare($query);
    $parameters_length = count($parameters);
    if ($parameters_length) {
      $statement->bind_param(str_repeat('s', $parameters_length), ...$parameters);
    }
    $statement->execute();
    return $statement->get_result();
  }
}

$mysqli = new CustomSql("localhost", "root", $env['PASSWORD'] ?? '', "slownik");
$mysqli->set_charset('utf8mb4');
if ($mysqli->connect_error) {
  echo "Connection error:" . $mysqli->connect_error;
}

return $mysqli
  ?>