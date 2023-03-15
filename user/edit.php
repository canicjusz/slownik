<?php
require_once('../connect-db.php');
require_once('../helpers.php');
session_start();
$query = "SELECT id, name, avatar, description FROM user WHERE id = ?";

if (!$result = $mysqli->execute_query($query, [$_SESSION['id']])) {
  echo $mysqli->error;
}
if ($result->num_rows == 1) {
  $user = $result->fetch_object();

  if (isset($_POST["avatar"]) || isset($_POST["description"]) || isset($_POST["name"])) {
    $new_avatar = $_FILES["avatar"]["tmp_name"];
    $edited = [];

    if (isset($new_avatar) && !empty($new_avatar)) {
      $new_avatar_name = $user->id . time() . '.' . pathinfo($_FILES["avatar"]["name"])['extension'];
      if (move_uploaded_file($new_avatar, "../avatars/$new_avatar_name")) {
        $edited['avatar'] = $new_avatar_name;
        $_SESSION['avatar'] = $new_avatar_name;
      }
    }

    replace_variable('name', $user->name, $_SESSION, $edited);
    replace_variable('description', $user->name, $_SESSION, $edited);
    $columns = implode(',', array_map('addQuestionMarks', array_keys($edited)));
    $query = "UPDATE user SET $columns WHERE id = ?";
    if ($mysqli->execute_query($query, [...array_values($edited), $user->id])) {
      header("Location: index.php?id=$user->id");
      exit;
    }
    echo $mysqli->error;
  }
}
?>

<head>
  <link rel="stylesheet" href="edit.css">
</head>

<?php require_once('../components/nav.php') ?>

<main class="main">
  <form action="" method="post" enctype="multipart/form-data" class="user">
    <div class="user__avatar-container">
      <img src="../avatars/<?= $user->avatar ?>" class="user__avatar" />
      <label class="user__avatar-plus" for="avatar"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
          width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" />
        </svg></label>
      <input class="user__avatar-input" type="file" id="avatar" name="avatar"
        accept="image/png, image/jpeg, image/jpg, image/gif">
    </div>
    <input class="user__input" type="text" name="name" value="<?= $user->name ?>" required placeholder="Nazwa">
    <textarea class="user__input" name="description" id="" cols="30" rows="10"
      placeholder="Opis"><?= $user->description ?></textarea>
    <button class="user__button">Zaakceptuj zmiany</button>
  </form>
</main>

<script src="edit.js"></script>

<?php require_once('../components/footer.php') ?>