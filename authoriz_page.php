<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>

<div class="auth_container">
    <div class="mx-auto">

        <h1>Вход</h1>
        <form action="/vendor/signin.php" method="post" enctype="multipart/form-data">
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
            <p>У вас еще нет аккаунта? - <a href="/reg_page.php">Зарегистрируйтесь</a>
                
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

</div>
<!-- подсветка полей -->
<script type="text/javascript">
    $('document').ready(function() {
        $(':submit').on('click', function() {
            $('input[required]').addClass('req');
        });
    });
    </script>
    <script type="text/javascript">
    $('document').ready(function() {
        $(':radio').on('click', function() {
            $('input[required]').removeClass('req');
        });
    });
</script>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>