<nav class="nav">
  <a href="/" class="nav__link-main">
    <img src="" alt="logo" class="nav__logo">
  </a>
  <form action="/results.php" method="get" class="nav__form search">
    <input type="search" id="search-input" name="q" required class="search__input" placeholder="Wyszukiwarka">
    <button class="search__button">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
        <path fill="none" d="M0 0h24v24H0z"></path>
        <path
          d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z">
        </path>
      </svg>
    </button>
  </form>
  <ul class="nav__list">
    <?php if (isset($_SESSION['id'], $_SESSION['name'], $_SESSION['avatar'])): ?>
      <li class="nav__element">
        <a href="/definition/add.php" class="nav__link">
          Dodaj definicję
        </a>
      </li>
      <li class="nav__element">
        <a href="/user/index.php?id=<?= $_SESSION['id'] ?>" class="nav__link">
          Mój profil
        </a>
      </li>
      <li class="nav__element">
        <a href="/logout.php" class="nav__link">
          Wyloguj się
        </a>
      </li>
    <?php else: ?>
      <li class="nav__element">
        <a href="/login.php" class="nav__link">Zaloguj się</a>
      </li>
      <li class="nav__element">
        <a href="/register.php" class="nav__link">Zarejestruj się</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>