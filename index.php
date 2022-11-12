<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand p-0" href="/">
          <img src="/images/main_title.png" alt="careauto.ru" height="50" />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" href="musicians.html">Сервисные центры</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="musicians.html">Написать в техподдержку</a>
            </li>
          </ul>
        </div>
        <a
              href="/authoriz_page.php"
              class="btn btn-primary"
          >
              Войти
          </a>
      </div>
    </nav>
    <div class="container text-center my-5">        
            <?php
                if (isset($_SESSION['message'])) {
                    echo '<p><div class="alert alert-success" role="alert">
                    ' . $_SESSION['message'] . '</div></p>';
                }
                unset ($_SESSION['message']);                
            ?>
    </dev>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>
