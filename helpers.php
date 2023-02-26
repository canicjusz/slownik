<?php
function replace_variable($variable, $old, &...$arrays)
{
  $new = $_POST[$variable];
  if (isset($new) && $new != $old) {
    foreach ($arrays as &$array) {
      $array[$variable] = $new;
    }
  }
}

function join_associative(&$item, $key)
{
  $item = $key . "='" . $item . "'";
  echo $item;
}

function check_adminship()
{
  $user_id = $_SESSION["id"];
  if (isset($user_id)) {
    global $mysqli;
    $user_query = "SELECT is_admin FROM user WHERE id = '$user_id'";
    if (!$user_result = $mysqli->query($user_query)) {
      echo $mysqli->error;
    }
  }
  return $user_result?->fetch_object()->is_admin ?? 0;
}

?>