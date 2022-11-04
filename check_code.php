<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/careauto.ru/assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <title>careauto</title>
</head>

<body>
    <div class="mx-auto">
        <p>Код был выслан на почту
            <?php echo $_SESSION['new_user']['email']; ?>
        </p>
        <div class="class mb-3">
            <form action="/careauto.ru/vendor/check_email_code.php" method="post">
                <div class="mb-3">
                    <label for="code" class="form-label">Укажите код с почты</label>
                    <input type="text" data-phone-pattern required placeholder="Код с почты" name="code"
                        class="form-control" id="code" />
                </div>
                <button name="confirm_code" type="submit" class="btn btn-primary">Подтвердить код</button>
            </form>

        </div>
        <form action="/careauto.ru/vendor/check_email_code.php" method="post">
            <div class="mb-3">
                <button name="resend" value=1 type="submit" class="btn btn-secondary">Отправить письмо еще раз</button>
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
</body>