<?php
require_once('../connect-db.php');
require_once('../helpers.php');
session_start();
$query = "SELECT id, name, avatar, description FROM user WHERE id = {$_SESSION['id']}";

if (!$result = $mysqli->query($query)) {
  echo $mysqli->error;
}
if ($result->num_rows == 1) {
  $user = $result->fetch_object();

  if(isset($_POST["avatar"]) || isset($_POST["description"]) || isset($_POST["name"])){
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

    array_walk($edited, 'join_associative');
    $query = "UPDATE user SET " . implode(',', $edited) . " WHERE id = $user->id";
    if ($mysqli->query($query)) {
      header("Location: index.php?id=$user->id");
      exit;
    }
    echo $mysqli->error;
  }
}
?>

<?php require_once('../components/nav.php') ?>

<main>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form__avatar-container">
      <img src="/avatars/<?= $user->avatar ?>" class="form__avatar" />
      <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/jpg, image/gif">
    </div>
    <input type="text" name="name" value="<?= $user->name ?>" required>
    <textarea name="description" id="" cols="30" rows="10"><?= $user->description ?></textarea>
    <button>zaakceptuj zmiany</button>
  </form>
</main>

<?php require_once('../components/footer.php') ?>