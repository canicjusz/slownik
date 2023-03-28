<?php
require_once(__DIR__ . '/connect-db.php');
session_start();

$count_query = 'SELECT COUNT(*) AS count FROM definition';
if (!$count_result = $mysqli->execute_query($count_query)) {
  echo $mysqli->error;
}
$count = $count_result->fetch_object()->count;
$random_int = rand(1, $count);
$definition_query = "SELECT * FROM (SELECT d.id, d.phrase, d.description_shortened, d.creation_date, d.last_edit_date, d.author_id, u.name, u.avatar FROM definition as d JOIN user as u ON d.author_id = u.id LIMIT $random_int) as a ORDER BY id DESC LIMIT 1";
if (!$definition_result = $mysqli->execute_query($definition_query)) {
  echo $mysqli->error;
}
$definition = $definition_result->fetch_object();

$new_definitions_query = "SELECT id, phrase, creation_date FROM definition ORDER BY creation_date DESC LIMIT 10";
if (!$new_definitions_result = $mysqli->execute_query($new_definitions_query)) {
  echo $mysqli->error;
}

$recently_edited_query = "SELECT id, phrase, last_edit_date FROM definition WHERE last_edit_date IS NOT NULL ORDER BY last_edit_date DESC LIMIT 10";
if (!$recently_edited_result = $mysqli->execute_query($recently_edited_query)) {
  echo $mysqli->error;
}

$best_definitions_query = "SELECT d.id, d.phrase, coalesce(SUM(r.opinion),0) AS ratio FROM ratio AS r RIGHT JOIN definition AS d ON r.definition_id=d.id GROUP BY d.id ORDER BY ratio DESC LIMIT 10";
if (!$best_definitions_result = $mysqli->execute_query($best_definitions_query)) {
  echo $mysqli->error;
}
?>

<head>
  <link rel="stylesheet" href="./styles/pages/index.css">
</head>

<?php require_once(__DIR__ . '/components/nav.php') ?>

<main class="main">
  <div>
    <h1 class="main__title">
      Klon
      <a href="https://www.miejski.pl">Miejskiego Słownika</a>
    </h1>
    <section class="random-section">
      <h2 class="random-section__title">Randomowa definicja:</h2>
      <?php if ($definition_result->num_rows): ?>
        <div class="random-section__content definition">
          <div class="definition__bubble">
            <h3 class="definition__title"><a href="definition?id=<?= $definition->id ?>"><?= $definition->phrase ?></a>
            </h3>
            <p class="definition__description">
              <?= strlen($definition->description_shortened) < 150 ? $definition->description_shortened : $definition->description_shortened . '... <a href="/definition?id=' . $definition->id . '">zobacz więcej</a>' ?>
            </p>
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
      <?php else: ?>
        <p class="random-section__paragraph">Brak definicji, pomóż rozwinąć słownik <a href="definition/add.php">tworząc
            <b>pierwszą</b> definicję.</a>
        </p>
      <?php endif; ?>
    </section>
  </div>
  <!-- todo: ikonki -->
  <div class="definition-lists">
    <div class="new-definition__container">
      <h2 class="new-definition__title">Ostatnio dodane:</h2>
      <ol class="new-definition__list">
        <?php while ($new_definition = $new_definitions_result->fetch_object()): ?>
          <li class="new-definition">
            <a class="new-definition__link" href="definition?id=<?= $new_definition->id ?>">
              <?= $new_definition->phrase ?>
            </a>
            <small class="new-definition__date">
              <?= $new_definition->creation_date ?>
            </small>
          </li>
        <?php endwhile; ?>
      </ol>
    </div>

    <div class="edited-definition__container">
      <h2 class="edited-definition__title">Ostatnio zmodyfikowane:</h2>
      <ol class="edited-definition__list">
        <?php while ($recently_edited = $recently_edited_result->fetch_object()): ?>
          <li class="edited-definition">
            <a class="edited-definition__link" href="definition?id=<?= $recently_edited->id ?>">
              <?= $recently_edited->phrase ?>
            </a>
            <small class="edited-definition__date">
              <?= $recently_edited->last_edit_date ?>
            </small>
          </li>
        <?php endwhile; ?>
      </ol>
    </div>

    <div class="best-definition__container">
      <h2 class="best-definition__title">Najlepsze definicje:</h2>
      <ol class="best-definition__list">
        <?php while ($best_definition = $best_definitions_result->fetch_object()): ?>
          <li class="best-definition">
            <a class="best-definition__link" href="definition?id=<?= $best_definition->id ?>">
              <?= $best_definition->phrase ?>
            </a>
            <small class="best-definition__date">
              <?= $best_definition->ratio ?>
            </small>
          </li>
        <?php endwhile; ?>
      </ol>
    </div>
  </div>
</main>

<?php require_once(__DIR__ . '/components/footer.php') ?>