<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/main.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>

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

    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="/images/site.webmanifest">
    <title>careauto</title>
</head>


<body class="auth">
    <div class="mx-auto">

        <h1>Вход</h1>
        <form action="/vendor/signin.php" method="post" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="user_type" value="1" id="btnradio1" autocomplete="off"
                    checked>
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

</body>

</html>