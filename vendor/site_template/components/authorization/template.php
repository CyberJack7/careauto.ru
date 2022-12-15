<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/authorization/script.js"></script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/authorization/style.css">

<?php //на странице авторизации может находиться только неавторизованный пользователь
if (!empty($_SESSION['user'])) {
    header('Location: /');
}
if (!empty($_SESSION['password_recovery'])) {
    unset($_SESSION['password_recovery']);
}
if (!isset($_SESSION['not_auth_user']['attempt'])) {
    $_SESSION['not_auth_user']['attempt'] = 3;
}
?>
<div class="container central content column">
    <h1>Вход</h1>
    <form action="signin.php" method="post" enctype="multipart/form-data">
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="user_type" value="1" id="btnradio1" autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="btnradio1">Автовладелец</label>

            <input type="radio" class="btn-check" name="user_type" value="0" id="btnradio2" autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
        </div>
        <div class="form_box">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Адрес электронной почты</label>
                <input type="email" required placeholder="email" name="email" class="form-control"
                    id="exampleInputEmail1" aria-describedby="emailHelp" />
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Пароль</label>
                <input type="password" required placeholder="password" name="password" class="form-control"
                    id="exampleInputPassword1" />
            </div>
        </div>
        <button class="btn btn-primary" id="auth_button_<?=$_SESSION['not_auth_user']['attempt']?>" name="auth_button" type="submit">Войти</button>
        <p>Забыли пароль? - <a href="/password_recovery/"> Восстановление пароля</a>
        <p>У вас еще нет аккаунта? - <a href="/registration/">Зарегистрируйтесь</a>

            <?php //блок вывода сообщений
            if (isset($_SESSION['message']['text'])) {
                if ($_SESSION['message']['type'] == 'success') {
                    echo '<p><div class="alert alert-success" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
                } elseif ($_SESSION['message']['type'] == 'warning') {
                    echo '<p><div class="alert alert-warning" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
                } elseif ($_SESSION['message']['type'] == 'danger') {
                    echo '<p><div class="alert alert-danger" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
                } elseif ($_SESSION['message']['type'] == 'info') {
                    echo '<p><div class="alert alert-info" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
                }
            }
            if ($_SESSION['not_auth_user']['attempt'] != 0) {
                unset($_SESSION['message']['text'], $_SESSION['message']['type']);
            }
            ?>

    </form>
</div>