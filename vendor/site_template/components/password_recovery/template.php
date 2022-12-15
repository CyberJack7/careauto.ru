<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/password_recovery/script.js"></script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/password_recovery/style.css">

<?php //на странице авторизации может находиться только неавторизованный пользователь
if (!empty($_SESSION['user'])) {
    header('Location: /');
}
?>
<div class="container central column content">
    <div id="form_email" class="form_box">
        <form class="panel"
            action="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/password_recovery/component.php"
            method="POST">
            <h1>Восстановление пароля</h1>
            <?php
            if (isset($_SESSION['password_recovery']['valid'])) {
                if ($_SESSION['password_recovery']['valid'] == false) {
                    echo '
                    <label for="exampleInputEmail1" class="form-label">Адрес электронной почты, указанный при
                        регистрации</label>
                        <input type="email" required placeholder="email" name="email" class="form-control" id="email"
                        aria-describedby="emailHelp" />
                        <button id="send_code" name="send_code" type="submit" class="btn btn-primary">Отправить код на
                            почту</button>';
                } else if ($_SESSION['password_recovery']['valid'] == true) {
                    echo '<label for="password" class="form-label">Введите новый пароль</label>
                <input type="password" required placeholder="password" name="password" class="form-control" id="password" pattern=".{5,20}" />
                <label for="password_confirm" class="form-label">Повторите новый пароль</label>
                <input type="password" required placeholder="password confirm" name="password_confirm" class="form-control"
                    id="password_confirm" pattern=".{5,20}" />
                <button id="change_password" name="change_password" type="submit" class="btn btn-primary">Изменить
                    пароль</button>';
                }
            } else {
                echo '
                    <label for="exampleInputEmail1" class="form-label">Адрес электронной почты, указанный при
                        регистрации</label>
                        <input type="email" required placeholder="email" name="email" class="form-control" id="email"
                        aria-describedby="emailHelp" />
                        <button id="send_code" name="send_code" type="submit" class="btn btn-primary">Отправить код на
                            почту</button>';
            }
            ?>
            <p>Желаете вернуться на страницу авторизации? - <a href="/authorization/">Авторизироваться</a></p>
        </form>
    </div>
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
    unset($_SESSION['message']['text'], $_SESSION['message']['type']);
    ?>

</div>