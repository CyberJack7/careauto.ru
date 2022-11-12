<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>

<div class="check_code_container">
    <div class="mx-auto">
        <p>Код был выслан на почту <?php echo $_SESSION['new_user']['email']; ?></p>
        <div class="class mb-3">
            <form action="/vendor/check_email_code.php" method="post">
                <div class="mb-3">
                    <label for="code" class="form-label">Укажите код с почты</label>
                    <input type="text" data-phone-pattern required placeholder="Код с почты" name="code"
                        class="form-control" id="code" />
                </div>
                <button name="confirm_code" type="submit" class="btn btn-primary">Подтвердить код</button>
            </form>

        </div>
        <form action="/vendor/check_email_code.php" method="post">
            <div class="mb-3">
                <label id="label_resend" for="resend" class="form-label">Повторная отправка кода будет доступна через 5
                    секунд</label>
                <button style="display: none" id="resend" name="resend" value=1 type="submit"
                    class="btn btn-secondary">Отправить письмо еще раз</button>
            </div>

        </form>
        <p>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<p><div class="alert alert-warning" role="alert">
                ' . $_SESSION['message'] . '</div></p>';
            }
            unset($_SESSION['message']);
            ?>
        </p>
    </div>
</div>

<script>
    setTimeout(function() {
    document.getElementById('resend').style.display = 'inline';
    document.getElementById('label_resend').style.display = 'none';
    }, 5000);
</script>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>