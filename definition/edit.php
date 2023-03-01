<?php
require_once('../helpers.php');
require_once('../connect-db.php');
session_start();
$query_id = $_GET["id"];
if (!isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header("Location: index.php?id=$query_id");
  exit;
}

$definition_query = "SELECT author_id, phrase, description, tags FROM definition WHERE id = '$query_id'";
if (!$definition_result = $mysqli->query($definition_query)) {
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

if (!empty($edited['description'])) {
  $new_description_shortened = substr($new_description, 0, 150);
  $edited['description_shortened'] = $new_description_shortened;
}

if (!empty($edited)) {
  $edited['last_edit_date'] = date('Y-m-d H:i:s');
  array_walk($edited, 'join_associative');
  $query = "UPDATE definition SET " . implode(',', $edited) . " WHERE id = $query_id";
  if ($mysqli->query($query)) {
    header("Location: index.php?id=$query_id");
    exit;
  }
  echo $mysqli->error;
}
?>

<?php require_once('../components/nav.php') ?>

<head>
  <link rel="stylesheet" href="edit.css">
</head>

<div class="main">
  <form action="" method="post" class="form">
    <label for="" class="form__label">
      Fraza, słowo
      <input contenteditable="true" class="form__input" type="text" name="phrase" value="<?= $definition->phrase ?>"
        id="phrase" required placeholder="Fraza, słowo"></input>
    </label>
    <label for="" class="form__label">
      Objaśnienie
      <textarea contenteditable="true" class="form__textarea" name="description" id="description" cols="30" rows="10"
        required placeholder="Objaśnienie"><?= $definition->description ?></textarea>
    </label>
    <label for="" class="form__label">
      Tagi
      <textarea contenteditable="true" class="form__textarea" name="tags" id="tags" req
        placeholder="Oddzielaj je przecinkiem: tag1,tag2"><?= $definition->tags ?></textarea>
    </label>
    <button class="form__button">Zatwierdź modyfikacje</button>
  </form>
</div>

<?php require_once('../components/footer.php') ?>