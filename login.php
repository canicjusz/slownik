<?php
require_once('connect-db.php');
session_start();
$email = $_POST['email'];
$password = $_POST['password'];
if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header('Location: /index.php');
  exit;
}
if (isset($email, $password)) {
  $query = "SELECT id, name, avatar FROM user WHERE email = '$email' AND password = '$password'";
  if (!$result = $mysqli->query($query)) {
    echo $mysqli->error;
  }
  if ($result->num_rows == 1) {
    $user = $result->fetch_object();
    $_SESSION['id'] = $user->id;
    $_SESSION['name'] = $user->name;
    $_SESSION['avatar'] = $user->avatar;
    header('Location: /index.php');
    exit;
  }
  echo 'nie ma takiego usera';
}
?>

<head>
  <link rel="stylesheet" href="login.css">
</head>

<?php require_once('components/nav.php') ?>

<main class="main">
  <form action="" method="post" class="form">
    <label for="email" class="form__label">
      Email:
      <input type="email" name="email" id="email" class="form__input" required>
    </label>
    <label for="password" class="form__label">
      Hasło:
      <input type="password" name="password" id="password" class="form__input" required>
    </label>
    <button class="form__button">
      Zaloguj się
    </button>
  </form>
</main>

<?php require_once('components/footer.php') ?>