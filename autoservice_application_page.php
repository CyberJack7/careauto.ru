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
                        <a class="nav-link active" aria-current="page" href="/">Заявки</a>
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
            <a href="/autoservice_profile_page.php" class="btn btn-primary">Профиль</a>
        </div>
    </nav>
    <!-- для заявок -->
    <nav>
        <div class="container">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new"
                    type="button" role="tab" aria-controls="nav-new" aria-selected="true">Ожидает подтверждения</button>
                <button class="nav-link" id="nav-wait-tab" data-bs-toggle="tab" data-bs-target="#nav-wait" type="button"
                    role="tab" aria-controls="nav-wait" aria-selected="false">Подтверждено</button>
                <button class="nav-link" id="nav-work-tab" data-bs-toggle="tab" data-bs-target="#nav-work" type="button"
                    role="tab" aria-controls="nav-work" aria-selected="false">В работе</button>
                <button class="nav-link" id="nav-done-tab" data-bs-toggle="tab" data-bs-target="#nav-done" type="button"
                    role="tab" aria-controls="nav-done" aria-selected="false">Выполнено</button>

            </div>
        </div>
    </nav>
    <div class="container">

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab"
                tabindex="0">
                Это текст с отступом.
            </div>
            <div class="tab-pane fade" id="nav-wait" role="tabpanel" aria-labelledby="nav-wait-tab" tabindex="0">
                Какой то текст 2</div>
            <div class="tab-pane fade" id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab" tabindex="0">
                какой то текст 3</div>
            <div class="tab-pane fade" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab" tabindex="0">
                Какой то текст 4
            </div>
        </div>


    </div>

    <!-- для заявок end -->
    <div class="container text-center my-5">
        <?php
        if (isset($_SESSION['message'])) {
            echo '<p><div class="alert alert-success" role="alert">
            ' . $_SESSION['message'] . '</div></p>';
        }
        unset($_SESSION['message']);
        ?>
    </div>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>