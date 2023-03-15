<?php
function replace_variable($variable, $old, &...$arrays)
{
  if(isset($_POST[$variable])){
    $new = $_POST[$variable];
    if (isset($new) && $new != $old) {
      foreach ($arrays as &$array) {
        $array[$variable] = $new;
      }
    }
  }
}

function addQuestionMarks($key){
  return $key . '=?';
}

function check_adminship()
{
  $user_id = $_SESSION["id"];
  if (isset($user_id)) {
    global $mysqli;
    $user_query = "SELECT is_admin FROM user WHERE id = ?";
    if (!$user_result = $mysqli->execute_query($user_query, [$user_id])) {
      echo $mysqli->error;
    }
  }
  return $user_result?->fetch_object()->is_admin ?? 0;
}
?>