<?php
require_once('../connect-db.php');
session_start();
if (!isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header('Location: /login.php');
  exit;
}
$new_phrase = $_POST['phrase'];
$new_description = $_POST['description'];
$new_tags = $_POST['tags'];

print_r($new_phrase);
print_r($new_description);
print_r($new_tags);
$new_description_shortened = substr($new_description, 0, 150);
if (isset($new_phrase, $new_description, $new_tags)) {
  $user_id = $_SESSION['id'];
  $query = "INSERT INTO definition (phrase, tags, description, description_shortened, author_id) VALUES ('$new_phrase', '$new_tags', '$new_description', '$new_description_shortened', '$user_id')";
  echo $query . $_SESSION['id'];
  if ($mysqli->query($query)) {
    header("Location: /definition?id=$mysqli->insert_id");
    exit;
  }
  echo $mysqli->error;
}
?>

<head>
  <link rel="stylesheet" href="/definition/add.css">
</head>

<?php require_once('../components/nav.php') ?>

<div class="main">
  <form action="" method="post" class="form">
    <label for="phrase" class="form__label">
      Fraza, słowo:
      <input type="text" name="phrase" value="<?= $_GET['phrase'] ?>" id="phrase" required
        placeholder="przykładowa nazwa" class="form__input">
    </label>
    <label for="description" class="form__label">
      Objaśnienie:
      <textarea name="description" id="description" cols="30" rows="10" required placeholder="ciekawy opis"
        class="form__textarea"></textarea>
    </label>
    <label for="tags" class="form__label">
      Wypisz tagi, możesz je oddzielić przecinkiem:
      <textarea name="tags" id="tags" cols="30" rows="10" placeholder="tag1,tag2" class="form__textarea"></textarea>
    </label>
    <button class="form__button">Dodaj definicję</button>
  </form>
</div>

<?php require_once('../components/footer.php') ?>