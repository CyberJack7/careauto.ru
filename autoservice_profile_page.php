<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand p-0" href="/">
                <img src="/images/main_title.png" alt="careauto.ru" height="50" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!--  -->
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                            href="/autoservice_application_page.php">Заявки</a>
                    </li>
                    <!--  -->
                    <li class="nav-item">
                        <a class="nav-link" href="">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Чаты с клиентами</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Аналитика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Написать в техподдержку</a>
                    </li>
                </ul>
            </div>
            <a href="/" class="btn btn-primary">Профиль</a>
        </div>
    </nav>
<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>