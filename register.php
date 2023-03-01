<?php
require_once('connect-db.php');
session_start();
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header('Location: index.php');
  exit;
}
if (isset($name, $email, $password)) {
  $query = "INSERT INTO user (name, email, password) VALUES ('$name', '$email', '$password')";
  if ($mysqli->query($query)) {
    header('Location: login.php');
    exit;
  }
  echo $mysqli->error;
}
?>

<head>
  <link rel="stylesheet" href="login.css">
</head>

<?php require_once('components/nav.php') ?>

<main class="main">
  <form action="" method="post" class="form">
    <label for="name" class="form__label">
      Nick:
      <input type="text" name="name" id="name" class="form__input" required>
    </label>
    <label for="email" class="form__label">
      Email:
      <input type="email" name="email" id="email" class="form__input" required>
    </label>
    <label for="password" class="form__label">
      Hasło:
      <input type="password" name="password" id="password" class="form__input" required>
    </label>
    <button class="form__button">
      Zarejestruj się
    </button>
  </form>
</main>

<?php require_once('components/footer.php') ?>