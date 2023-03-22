<?php
require_once(__DIR__ . '/connect-db.php');
session_start();

if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header('Location: index.php');
  exit;
}

function isEmailInDB($email)
{
  global $mysqli;
  $query = "SELECT * FROM user WHERE email=?";
  if ($result = $mysqli->execute_query($query, [$email])) {
    return $result->num_rows;
  }
}

if (isset($_POST['email'], $_POST['password'])) {

  $email = $_POST['email'];
  $password = $_POST['password'];

  if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
    header('Location: index.php');
    exit;
  }

  $query = "SELECT id, name, avatar, password FROM user WHERE email = ?";

  if (!$result = $mysqli->execute_query($query, [$email])) {
    echo $mysqli->error;
  }

  $user = $result->fetch_object();

  if ($result->num_rows == 1 && password_verify($password, $user->password)) {
    $_SESSION['id'] = $user->id;
    $_SESSION['name'] = $user->name;
    $_SESSION['avatar'] = $user->avatar;
    header('Location: index.php');
    exit;
  }
  if (!isEmailInDB($email)) {
    $error_message = 'Użytkownik z tym emailem nie istnieje, zarejestuj się <a href="register.php">tutaj</a>.';
  } else {
    $error_message = 'Nieprawidłowe hasło.';
  }
}
?>

<head>
  <link rel="stylesheet" href="login.css">
</head>

<?php require_once(__DIR__ . '/components/nav.php') ?>

<main class="main">
  <form action="" method="post" class="form">
    <label for="email" class="form__label">
      Email:
      <input type="email" name="email" id="email" class="form__input" require_onced>
    </label>
    <label for="password" class="form__label">
      Hasło:
      <input type="password" name="password" id="password" class="form__input" require_onced>
    </label>
    <button class="form__button">
      Zaloguj się
    </button>
  </form>
  <?php if (isset($error_message)): ?>
    <div class="error">
      <?= $error_message ?>
    </div>
  <?php endif; ?>
</main>

<?php require_once(__DIR__ . '/components/footer.php') ?>