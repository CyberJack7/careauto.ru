<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_banlist/script.js">
</script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_banlist/style.css">

<?php
require_once PATH_CONNECT;
$pdo = conn();
//на странице заявок админа может находиться только админ
if (!($_SESSION['user']['user_type'] == 'admin')) {
    header('Location: /');
}
?>

<nav>
    <div class="container">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-client-tab" data-bs-toggle="tab" data-bs-target="#nav-client"
                type="button" role="tab" aria-controls="nav-client" aria-selected="true">Бан-лист клиентов</button>
            <button class="nav-link" id="nav-autoservice-tab" data-bs-toggle="tab" data-bs-target="#nav-autoservice"
                type="button" role="tab" aria-controls="nav-autoservice" aria-selected="false">Бан-лист СЦ</button>
        </div>
    </div>
</nav>


<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-client" role="tabpanel" aria-labelledby="nav-client-tab"
            tabindex="0">
            <div id="accordion1" class="accordion">
                <?php
                $arBanList = getBanlist("client");
                if (empty($arBanList)) {
                    echo '<div class="alert alert-primary" role="alert">
                    Нет пользователей в бан-листе!
                  </div>';
                } else {

                    foreach ($arBanList as $ban_user) {

                        echo '<div id="banned_"' . $ban_user['id'] . '>
                <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-heading' . $ban_user['id'] . '">
                <button value="' . $ban_user['id'] . '" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $ban_user['id'] . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $ban_user['id'] . '">' .
                            $ban_user['name_user'] . ' от ' . $ban_user['date'] .
                            '</button>
                        </h2>
                <div id="panelsStayOpen-collapse' . $ban_user['id'] . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $ban_user['id'] . '">
                <div class="accordion-body">' .
                            'Имя пользователя: ' . $ban_user['name_user'] . '</br> ' .
                            'Телефон пользователя: ' . $ban_user['phone_user'] . '</br> ' .
                            'Почта пользователя: ' . $ban_user['email_user'] . '</br> ' .
                            'Имя судьи(админа): ' . $ban_user['name_admin'] . '</br> ' .
                            'Почта судьи(админа): ' . $ban_user['email_admin'] . '</br> ' .
                            'Причина блокировки: ' . $ban_user['text'] . '</br>
                        Дата блокировки: ' . $ban_user['date'] . '</br>
                        <div class="buttons">
                        <button onclick="unban(this)" role="button" name="accept" id="accept_btn_' . $ban_user['id'] . '" value="' . $ban_user['id'] . '" class="btn btn-primary" type="button" >Разблокировать пользователя</button>
                        </div>
                        </div></div></div></div>';
                    }
                }




                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-autoservice" role="tabpanel" aria-labelledby="nav-autoservice-tab"
            tabindex="0">
            <div class="accordion" id="accordion2">
                <?php

                $arBanList = getBanlist("autoservice");
                if (empty($arBanList)) {
                    echo '<div class="alert alert-primary" role="alert">
                    Нет пользователей в бан-листе!
                  </div>';
                } else {

                    foreach ($arBanList as $ban_user) {

                        echo '<div id="banned_"' . $ban_user['id'] . '>
                    <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-heading' . $ban_user['id'] . '">
                    <button value="' . $ban_user['id'] . '" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $ban_user['id'] . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $ban_user['id'] . '">' .
                            $ban_user['name_user'] . ' от ' . $ban_user['date'] .
                            '</button>
                </h2>
                <div id="panelsStayOpen-collapse' . $ban_user['id'] . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $ban_user['id'] . '">
                <div class="accordion-body">' .
                            'Имя пользователя: ' . $ban_user['name_user'] . '</br> ' .
                            'Телефон пользователя: ' . $ban_user['phone_user'] . '</br> ' .
                            'Почта пользователя: ' . $ban_user['email_user'] . '</br> ' .
                            'Имя судьи(админа): ' . $ban_user['name_admin'] . '</br> ' .
                            'Почта судьи(админа): ' . $ban_user['email_admin'] . '</br> ' .
                            'Причина блокировки: ' . $ban_user['text'] . '</br>
                Дата блокировки: ' . $ban_user['date'] . '</br>
                <div class="buttons">
                <button onclick="unban(this)" role="button" name="accept" id="accept_btn_' . $ban_user['id'] . '" value="' . $ban_user['id'] . '" class="btn btn-primary" type="button" >Разблокировать пользователя</button>
                </div>
                        </div></div></div></div>';
                    }
                }
                ?>
            </div>
        </div>

    </div>
</div>