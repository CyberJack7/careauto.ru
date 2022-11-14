<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/authorization/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/authorization/style.css">

<?php //на странице авторизации может находиться только неавторизованный пользователь
    if (!empty($_SESSION['user'])) {
        header('Location: /');
    }
?>

<div class="auth_container">
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
        <button id="auth_button" name="auth_button" type="submit" class="btn btn-primary">Войти</button>
        <p>У вас еще нет аккаунта? - <a href="/registration/">Зарегистрируйтесь</a>
            
        <?php
        if (isset($_SESSION['message'])) {
            if (substr($_SESSION['message'], 0, 52) == "Данный аккаунт заблокирован") {
                echo '<p><div class="alert alert-danger" role="alert">
                ' . $_SESSION['message'] . '</div></p>';
            } elseif (substr($_SESSION['message'], 0, 22) == "Регистрация") {
                echo '<p><div class="alert alert-success" role="alert">
                ' . $_SESSION['message'] . '</div></p>';
            } else {
                echo '<p><div class="alert alert-warning" role="alert">
                ' . $_SESSION['message'] . '</div></p>';
            }
        }
        unset($_SESSION['message']);
        ?>

    </form>
</div>