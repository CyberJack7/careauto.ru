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
    <title>careauto</title>
</head>


<body>
    <div class="mx-auto">

        <h1>Авторизация</h1>
        <form action="/careauto.ru/vendor/signin.php" method="post">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="user_type" value="1" id="btnradio1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Автовладелец</label>

                <input type="radio" class="btn-check" name="user_type" value="0" id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Адрес электронной почты</label>
                <input type="email" placeholder="email" name="email" class="form-control" id="exampleInputEmail1"
                    aria-describedby="emailHelp" />
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Пароль</label>
                <input type="password" placeholder="password" name="password" class="form-control"
                    id="exampleInputPassword1" />
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
            <p>У вас еще нет аккаунта? - <a href="/careauto.ru/reg_page.php">Зарегестрируйтесь</a>
                <?php
                    if (isset($_SESSION['result'])) {
                        if ($_SESSION['result'] == 1) {
                            echo '<p><div class="alert alert-warning" role="alert">
                            ' . $_SESSION['message'] . '</div></p>';
                        } else {
                            echo '<p><div class="alert alert-success" role="alert">
                            ' . $_SESSION['message'] . '</div></p>';
                        }
                    }
                    unset($_SESSION['message'], $_SESSION['result']);
                ?>

        </form>

    </div>

</body>

</html>