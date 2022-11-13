<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/navigation.main/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/navigation.main/style.css">

<div class="navbar navbar-expand-lg navbar-dark bg-dark navbar_custom">
    <div class="container">
        <a class="navbar-brand p-0" href="/">
            <img src="/images/main_title.png" alt="careauto.ru" height="50" />
        </a>
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <?php
            //вкладки и кнопки для разных типов пользователей
            if (empty($_SESSION['user'])) { //неавторизованный
                ?>
                <li class="nav-item">
                    <a class="nav-link active" 
                        href="">Сервисные центры</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Написать в техподдержку</a>
                </li>                
            </ul>
        </div>
        <a class="btn btn-primary" href="/authorization/">Войти</a>
                <?php
            } elseif ($_SESSION['user']['user_type'] == 'admin') { //admin
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin_check_reg_page.php">Заявки на регистрацию</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin_complaint.php">Жалобы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin_banlist.php">Бан-лист</a>
                </li>                
            </ul>
        </div>
        <a class="btn btn-primary" href="/">Выйти</a>
                <?php
            } elseif ($_SESSION['user']['user_type'] == 'client') { //автовладелец
                if (mb_strpos($_SERVER['REQUEST_URI'], '/profile/') !== false) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="">Написать в техподдержку</a>
                </li>
            </ul>
        </div>
        <a class="btn btn-primary" href="/">Выйти</a>
                <?php
                } else {
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/my_auto/') !== false) {?>active<?php }?>" 
                        aria-current="page" href="/my_auto/">Мои авто</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Сервисные центры</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/">Заявки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (mb_strpos($_SERVER['REQUEST_URI'], '/cars_serv_history/') !== false) {?>active<?php }?>" 
                        href="/cars_serv_history/">История обслуживания</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Написать в техподдержку</a>
                </li>                
            </ul>
        </div>
        <a class="btn btn-primary" href="/profile/">Профиль</a>
            <?php }
        } else { //автосервис
            if (mb_strpos($_SERVER['REQUEST_URI'], '/profile/') !== false) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="">Написать в техподдержку</a>
                </li>
            </ul>
        </div>
        <a class="btn btn-primary" href="">Выйти</a>
                <?php
                } else {
                ?>
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/autoservice_application_page.php">Заявки</a>
                </li>
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
        <a class="btn btn-primary" href="/profile/">Профиль</a>                    
            <?php
            }
        }
        ?>
    </div>
</div>