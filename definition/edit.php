<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/helpers.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/connect-db.php');
session_start();
$query_id = $_GET["id"];
if (!isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header("Location: index.php?id=$query_id");
  exit;
}

$definition_query = "SELECT author_id, phrase, description, tags FROM definition WHERE id = ?";
if (!$definition_result = $mysqli->execute_query($definition_query, [$query_id])) {
  echo $mysqli->error;
}

$definition = $definition_result->fetch_object();
if (!($definition->author_id == $_SESSION['id'] || check_adminship())) {
  header("Location: index.php?id=$query_id");
  exit;
}

$edited = [];

replace_variable('phrase', $definition->phrase, $edited);
replace_variable('description', $definition->description, $edited);
replace_variable('tags', $definition->tags, $edited);

if (isset($_POST['description'])) {
  $new_description_shortened = substr($_POST['description'], 0, 150);
  $edited['description_shortened'] = $new_description_shortened;
}

if (!empty($edited)) {
  $edited['last_edit_date'] = date('Y-m-d H:i:s');
  $columns = implode(',', array_map('addQuestionMarks', array_keys($edited)));
  $query = "UPDATE definition SET $columns WHERE id = ?";
  if ($mysqli->execute_query($query, [...array_values($edited), $query_id])) {
    header("Location: index.php?id=$query_id");
    exit;
  }
  echo $mysqli->error;
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/nav.php') ?>

<head>
  <link rel="stylesheet" href="edit.css">
</head>

<div class="main">
  <form action="" method="post" class="form">
    <label for="" class="form__label">
      Fraza, słowo
      <input contenteditable="true" class="form__input" type="text" name="phrase" value="<?= $definition->phrase ?>"
        id="phrase" require_onced placeholder="Fraza, słowo"></input>
    </label>
    <label for="" class="form__label">
      Objaśnienie
      <textarea contenteditable="true" class="form__textarea" name="description" id="description" cols="30" rows="10"
        require_onced placeholder="Objaśnienie"><?= $definition->description ?></textarea>
    </label>
    <label for="" class="form__label">
      Tagi
      <textarea contenteditable="true" class="form__textarea" name="tags" id="tags" req
        placeholder="Oddzielaj je przecinkiem: tag1,tag2"><?= $definition->tags ?></textarea>
    </label>
    <button class="form__button">Zatwierdź modyfikacje</button>
  </form>
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php') ?>