<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/connect-db.php');
session_start();
$keywords = $_GET["q"] ?? '';
$user_id = $_SESSION['id'] ?? 0;
$_SESSION['uri'] = $_SERVER['REQUEST_URI'];
$definitions_query = "SELECT d.id, d.phrase, d.description_shortened, d.creation_date, d.last_edit_date, d.author_id, u.name, u.avatar, 
(SELECT IFNULL(SUM(opinion), 0) FROM ratio WHERE definition_id=d.id) as ratio, 
IF(0 < ?, (SELECT opinion FROM ratio WHERE definition_id=d.id AND user_id=?), '0') as opinion
FROM definition as d JOIN user as u ON d.author_id = u.id WHERE MATCH(d.phrase,d.description,d.tags) AGAINST (? IN NATURAL LANGUAGE MODE) ORDER BY opinion DESC";

$definitions_result = $mysqli->execute_query($definitions_query, [$user_id, $user_id, $keywords]);
?>

<head>
  <link rel="stylesheet" href="results.css">
</head>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/nav.php') ?>

<div class="main">
  <div>
    <h1 class="main__title">Wyniki wyszukiwania:</h1>
    <div class="results-section">
      <?php if ($definitions_result->num_rows == 0): ?>
        brak wyników
      <?php else: ?>
        <?php while ($definition = $definitions_result->fetch_object()): ?>
          <div class="definition">
            <div class="definition__bubble">
              <h2 class="definition__title">
                <a href="definition?id=<?= $definition->id ?>"><?= $definition->phrase ?></a>
              </h2>
              <p class="definition__description">
                <?= strlen($definition->description_shortened) < 150 ? $definition->description_shortened : $definition->description_shortened . '... <a href="definition?id=' . $definition->id . '">zobacz więcej</a>' ?>
              </p>
              <div class="definition__opinion">
                <a href="definition/upvote.php?id=<?= $definition->id ?>" class="definition__thumb">
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
                <a href="definition/downvote.php?id=<?= $definition->id ?>" class="definition__thumb">
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
            </div>
            <a class="definition__avatar-container" href="user/index.php?id=<?= $definition->author_id ?>">
              <img class="definition__avatar" src="avatars/<?= $definition->avatar ?>" alt="">
            </a>
            <a class="definition__name" href="user/index.php?id=<?= $definition->author_id ?>">
              <?= $definition->name ?>
            </a>
            <small class="definition__date">
              <?= $definition->last_edit_date ? $definition->creation_date . ', ostatnia zmiana: ' . $definition->last_edit_date : $definition->creation_date ?>
            </small>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
      <?php if ($_SESSION['id']): ?>
        <div class="definition definition--right">
          <form action="add.php" method="post" class="definition__bubble form">
            <label for="" class="form__label">
              Fraza, słowo
              <input contenteditable="true" class="form__input" type="text" name="phrase" value="<?= $keywords ?>"
                id="phrase" require_onced placeholder="Fraza, słowo"></input>
            </label>
            <label for="" class="form__label">
              Objaśnienie
              <textarea contenteditable="true" class="form__textarea" name="description" id="description" cols="30"
                rows="10" require_onced placeholder="Objaśnienie"></textarea>
            </label>
            <label for="" class="form__label">
              Tagi
              <textarea contenteditable="true" class="form__textarea" name="tags" id="tags" req
                placeholder="Oddzielaj je przecinkiem: tag1,tag2"></textarea>
            </label>
            <button class="form__button">Dodaj własną definicję!</button>
          </form>
          <span class="definition__avatar-container">
            <img class="definition__avatar" src="avatars/<?= $_SESSION["avatar"] ?>" alt="">
          </span>
          <span class="definition__name">
            <?= $_SESSION["name"] ?>
          </span>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- <p>Chcesz podzielić się swoją definicją? <a href="/definition/add.php?phrase=<?= $keywords ?>">Dodaj ją!</a>
  </p> -->
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php') ?>