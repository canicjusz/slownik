<?php
require_once(__DIR__ . '/../connect-db.php');
session_start();

$query = "SELECT id, name, avatar, creation_date, description FROM user WHERE id = ?";

if (!$result = $mysqli->execute_query($query, [$_GET['id']])) {
  echo $mysqli->error;
}

if (!$result->num_rows) {
  header("Location: ../404.php");
  exit;
}

$user = $result->fetch_object();
?>

<head>
  <link rel="stylesheet" href="index.css">
</head>

<?php require_once(__DIR__ . '/../components/nav.php') ?>
<!-- todo: dodac body tagi -->
<!-- todo: errory -->
<main class="main">
  <div class="user">
    <div class="user__avatar-container">
      <img src="../avatars/<?= $user->avatar ?>" alt="" class="user__avatar">
    </div>
    <h2 class="user__name">
      <?= $user->name ?>
      <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $user->id): ?>
        <a href="edit.php">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z" />
            <path
              d="M15.728 9.686l-1.414-1.414L5 17.586V19h1.414l9.314-9.314zm1.414-1.414l1.414-1.414-1.414-1.414-1.414 1.414 1.414 1.414zM7.242 21H3v-4.243L16.435 3.322a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414L7.243 21z" />
          </svg>
        </a>
      <?php endif; ?>
    </h2>
    <small class="user__date">Dołączył(a)
      <?= $user->creation_date ?>
    </small>
    <p class="user__description">
      <?= $user->description ?>
    </p>
  </div>
</main>

<?php require_once(__DIR__ . '/../components/footer.php') ?>