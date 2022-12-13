<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/navigation.main/script.js"></script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/navigation.main/style.css">

<div class="navbar navbar-expand-lg navbar-dark bg-dark navbar_custom">
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
                <?php
                //вкладки и кнопки для разных типов пользователей
                if (empty($_SESSION['user'])) { //неавторизованный
                ?>
            </ul>
        </div>
        <?php
                } elseif ($_SESSION['user']['user_type'] == 'admin') { //admin 
    ?>
        <li class="nav-item">
            <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/admin_reg_applications/') !== false) { ?>active<?php } ?>"
                href="/admin_reg_applications/">Заявки на регистрацию</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/admin_complaint/') !== false) { ?>active<?php } ?>"
                href="/admin_complaint/">Жалобы от пользователей</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/admin_banlist/') !== false) { ?>active<?php } ?>"
                href="/admin_banlist/">Бан-лист пользователей</a>
        </li>
        </ul>
    </div>
    <p style="color: white; margin-right: 20px !important"><?= $_SESSION['user']['name'] ?></p>
    <button class="btn btn-primary" id="logout_btn" type="button">Выйти</button>
    <?php
                } elseif ($_SESSION['user']['user_type'] == 'client') { //автовладелец 
?>
    <li class="nav-item">
        <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/my_auto/') !== false) { ?>active<?php } ?>"
            aria-current="page" href="/my_auto/">Мои авто</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/service_centres/') !== false) { ?>active<?php } ?>"
            aria-current="page" href="/service_centres/">Сервисные центры</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/client_applications/') !== false) { ?>active<?php } ?>"
            aria-current="page" href="/client_applications/">Заявки</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/cars_serv_history/') !== false) { ?>active<?php } ?>"
            aria-current="page" href="/cars_serv_history/">История обслуживания</a>
    </li>
    </ul>
</div>
<?php if (mb_strpos($_SERVER['REQUEST_URI'], '/profile/') !== false) { //если в профиле 
?>
<p style="color: white; margin-right: 20px !important"><?= $_SESSION['user']['name'] ?></p>
<button class="btn btn-primary" id="logout_btn" type="button">Выйти</button>
<?php } else { ?>
<p style="color: white; margin-right: 20px !important"><?= $_SESSION['user']['name'] ?></p>
<a class="btn btn-primary" href="/profile/">Профиль</a>
<?php }
                } else { //автосервис 
?>
<li class="nav-item">
    <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/autoservice_applications/') !== false) { ?>active<?php } ?>"
        aria-current="page" href="/autoservice_applications/">Заявки</a>
</li>
<li class="nav-item">
    <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/autoservice_service/') !== false) { ?>active<?php } ?>"
        aria-current="page" href="/autoservice_service/">Услуги</a>
</li>
<li class="nav-item">
    <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/autoservice_archive/') !== false) { ?>active<?php } ?>"
        aria-current="page" href="/autoservice_archive/">Архив</a>
</li>
</ul>
</div>
<?php if (mb_strpos($_SERVER['REQUEST_URI'], '/profile/') !== false) { //если в профиле 
?>
<p style="color: white; margin-right: 20px !important"><?= $_SESSION['user']['name'] ?></p>
<button class="btn btn-primary" id="logout_btn" type="button">Выйти</button>
<?php } else { ?>
<p style="color: white; margin-right: 20px !important"><?= $_SESSION['user']['name'] ?></p>
<a class="btn btn-primary" href="/profile/">Профиль</a>
<?php }
                } ?>
</div>
</div>