<?php
require_once('connect-db.php');
session_start();

if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])) {
  header('Location: index.php');
  exit;
}

function isNameInDB($name){
  global $mysqli;
  $query = "SELECT * FROM user WHERE name='$name'";
  if ($result = $mysqli->query($query)) {
    return $result->num_rows;
  }
}

function isEmailInDB($email){
  global $mysqli;
  $query = "SELECT * FROM user WHERE email='$email'";
  if ($result = $mysqli->query($query)) {
    return $result->num_rows;
  }
}

$error_message = '';

if(isset($_POST['email'], $_POST['password'], $_POST['name'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (!isset($name, $email, $password)){
    exit;
  }
  
  $existsName = isNameInDB($name);
  $existsEmail = isEmailInDB($email);

  if (!($existsName || $existsEmail)) {
    $query = "INSERT INTO user (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($mysqli->query($query)) {
      header('Location: login.php');
      exit;
    }
  }
  
  if($existsName){
    $error_message .= 'Ta nazwa użytkownika jest już zajęta.';
  }
  
  if($existsEmail){
    if($error_message != ''){
      $error_message .= '<br>';
    }
    $error_message .= 'Ten email jest już w użyciu, zaloguj się <a href="login.php">tutaj</a>.';
  }
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
    <?php if($error_message != ''): ?>
      <div class="error">
        <?= $error_message ?>
      </div>
    <?php endif; ?>
  <div>
    
  </div>
</main>

<?php require_once('components/footer.php') ?>