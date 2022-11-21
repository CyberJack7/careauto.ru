<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/confirmation_code/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/confirmation_code/style.css">

<?php //на странице подтверждения регистрации может находиться только неавторизованный или меняющий почту пользователь
    if (empty($_SESSION['user']['new_email']) && empty($_SESSION['new_user'])) {
        header('Location: /');
        exit;
    }
?>

<div class="container central column">
    <div class="class mb-3">
        <h2>Подтверждение регистрации</h2>
        <p>Код был выслан на почту:<br><b><?php
            if (!empty($_SESSION['new_user']['email'])) {
                echo $_SESSION['new_user']['email'];
            } else {
                echo $_SESSION['user']['new_email'];
            } ?></b></p>
        <form action="check_email_code.php" method="post">
            <div class="mb-3">
                <label for="code" class="form-label">Укажите код с почты</label>
                <input type="text" required placeholder="Код с почты" name="code"
                    class="form-control" id="code" />
            </div>
            <button name="confirm_code" type="submit" class="btn btn-primary">Подтвердить код</button>
        </form>
    </div>
    <form action="check_email_code.php" method="post">
        <div class="mb-3">
            <label id="label_resend" for="resend" class="form-label">Повторная отправка кода будет доступна через 5
                секунд</label>
            <button style="display: none" id="resend" name="resend" value=1 type="submit"
                class="btn btn-secondary">Отправить письмо еще раз</button>
        </div>

    </form>
    <p>
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
    </p>
</div>