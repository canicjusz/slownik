<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/connect-db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/helpers.php');
session_start();
$query_id = $_GET['id'];
$user_id = $_SESSION['id'] ?? 0;
$_SESSION['uri'] = $_SERVER['REQUEST_URI'];

$definition_query = "SELECT d.id, d.phrase, d.tags, d.description, d.creation_date, d.last_edit_date, d.author_id, u.name, u.avatar, 
(SELECT IFNULL(SUM(opinion), 0) FROM ratio WHERE definition_id=d.id) as ratio,
IF(0 < ?, (SELECT opinion FROM ratio WHERE definition_id=d.id AND user_id=?), '0') as opinion,
d.author_id = ? as owned
FROM definition as d JOIN user as u ON d.author_id = u.id WHERE d.id = ?";

if (!$definition_result = $mysqli->execute_query($definition_query, [$user_id, $user_id, $user_id, $query_id])) {
  echo $mysqli->error;
}

if (!$definition_result->num_rows) {
  header("Location: ../404.php");
  exit;
}

$definition = $definition_result->fetch_object();
?>

<head>
  <link rel="stylesheet" href="../definition/index.css">
</head>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/nav.php') ?>

<div class="main">
  <div>
    <div class="middle-section">
      <div class="definition">
        <div class="definition__bubble">
          <h2 class="definition__title">
            <a href="?id=<?= $definition->id ?>"><?= $definition->phrase ?></a>
          </h2>
          <p class="definition__description">
            <?= $definition->description ?>
          </p>
          <div class="definition__last-row">
            <div class="definition__opinion">
              <a href="upvote.php?id=<?= $definition->id ?>" class="definition__thumb">
                <?php if ($definition->opinion == 1): ?>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M2 9h3v12H2a1 1 0 0 1-1-1V10a1 1 0 0 1 1-1zm5.293-1.293l6.4-6.4a.5.5 0 0 1 .654-.047l.853.64a1.5 1.5 0 0 1 .553 1.57L14.6 8H21a2 2 0 0 1 2 2v2.104a2 2 0 0 1-.15.762l-3.095 7.515a1 1 0 0 1-.925.619H8a1 1 0 0 1-1-1V8.414a1 1 0 0 1 .293-.707z" />
                  </svg>
                <?php else: ?>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M14.6 8H21a2 2 0 0 1 2 2v2.104a2 2 0 0 1-.15.762l-3.095 7.515a1 1 0 0 1-.925.619H2a1 1 0 0 1-1-1V10a1 1 0 0 1 1-1h3.482a1 1 0 0 0 .817-.423L11.752.85a.5.5 0 0 1 .632-.159l1.814.907a2.5 2.5 0 0 1 1.305 2.853L14.6 8zM7 10.588V19h11.16L21 12.104V10h-6.4a2 2 0 0 1-1.938-2.493l.903-3.548a.5.5 0 0 0-.261-.571l-.661-.33-4.71 6.672c-.25.354-.57.644-.933.858zM5 11H3v8h2v-8z" />
                  </svg>
                <?php endif; ?>
              </a>
              <span class="definition__ratio" ratio="<?= $definition->ratio ?>">
                <?= $definition->ratio ?>
              </span>
              <a href="downvote.php?id=<?= $definition->id ?>" class="definition__thumb">
                <?php if ($definition->opinion == -1): ?>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M22 15h-3V3h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zm-5.293 1.293l-6.4 6.4a.5.5 0 0 1-.654.047L8.8 22.1a1.5 1.5 0 0 1-.553-1.57L9.4 16H3a2 2 0 0 1-2-2v-2.104a2 2 0 0 1 .15-.762L4.246 3.62A1 1 0 0 1 5.17 3H16a1 1 0 0 1 1 1v11.586a1 1 0 0 1-.293.707z" />
                  </svg>
                <?php else: ?>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M9.4 16H3a2 2 0 0 1-2-2v-2.104a2 2 0 0 1 .15-.762L4.246 3.62A1 1 0 0 1 5.17 3H22a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-3.482a1 1 0 0 0-.817.423l-5.453 7.726a.5.5 0 0 1-.632.159L9.802 22.4a2.5 2.5 0 0 1-1.305-2.853L9.4 16zm7.6-2.588V5H5.84L3 11.896V14h6.4a2 2 0 0 1 1.938 2.493l-.903 3.548a.5.5 0 0 0 .261.571l.661.33 4.71-6.672c.25-.354.57-.644.933-.858zM19 13h2V5h-2v8z" />
                  </svg>
                <?php endif; ?>
              </a>
            </div>
            <?php if (check_adminship() || $definition->owned): ?>
              <div class="definition__button-container">
                <a class="definition__button" href="edit.php?id=<?= $definition->id ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M15.728 9.686l-1.414-1.414L5 17.586V19h1.414l9.314-9.314zm1.414-1.414l1.414-1.414-1.414-1.414-1.414 1.414 1.414 1.414zM7.242 21H3v-4.243L16.435 3.322a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414L7.243 21z" />
                  </svg>
                </a>
                <a class="definition__button" href="remove.php?id=<?= $definition->id ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M7 4V2h10v2h5v2h-2v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6H2V4h5zM6 6v14h12V6H6zm3 3h2v8H9V9zm4 0h2v8h-2V9z" />
                  </svg>
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <a class="definition__avatar-container" href="../user/index.php?id=<?= $definition->author_id ?>">
          <img class="definition__avatar" src="../avatars/<?= $definition->avatar ?>" alt="">
        </a>
        <a class="definition__name" href="../user/index.php?id=<?= $definition->author_id ?>">
          <?= $definition->name ?>
        </a>
        <small class="definition__date">
          <?= $definition->last_edit_date ? $definition->creation_date . ', ostatnia zmiana: ' . $definition->last_edit_date : $definition->creation_date ?>
        </small>
      </div>
    </div>
    <a class="see-also" href="../results.php?q=<?= $definition->phrase ?>">Zobacz inne, powiązane definicje</a>
  </div>
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php') ?>