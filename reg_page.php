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
    <!-- JQueary -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $("#" + $(".radio:checked").val()).show();
        $(".btn-check").change(function() {
            $(".radio-blocks").hide();
            $("#" + $(this).val()).show();
        });
    });
    </script>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <title>careauto</title>
</head>

<body>
    <div class="mx-auto">
        <h1>Регистрация</h1>
        <p>Выберите тип пользователя</p>
        <form action="/careauto.ru/vendor/signup.php" method="post" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" value="client" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                    checked>
                <label class="btn btn-outline-primary" for="btnradio1">Клиент</label>

                <input type="radio" value="autoservice" class="btn-check" name="btnradio" id="btnradio2"
                    autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
            </div>
            <div class="mb-3">
                <label for="email_client" class="form-label">Адрес электронной почты</label>
                <input type="email" placeholder="email" name="email" class="form-control" value="<?php 
                    if(isset($_SESSION['email'])) 
                        echo $_SESSION['email'];
                    else
                        echo null; ?>" id="exampleInputEmail1" aria-describedby="emailHelp" />
                <div class="invalid-feedback">
                    Please choose a username.
                </div>
            </div>
            <div class="mb-3">
                <label for="phone_client" class="form-label">Укажите ваш номер телефона</label>
                <input type="text" placeholder="Номер телефона" name="phone" class="form-control" id="phone_client" />
            </div>
            <div id="client" class="radio-blocks">
                <div class="mb-3">
                    <label for="name_client" class="form-label">ФИО</label>
                    <input type="text" placeholder="Фамилия Имя Отчество" name="name_client" class="form-control"
                        id="name_client" />
                </div>
                <div class="mb-3">
                    <label for="city_client" class="form-label">Укажите ваш город</label>
                    <input type="text" placeholder="Город" name="city_id" class="form-control" id="city_client" />
                </div>
            </div>

            <div id="autoservice" class="radio-blocks" style="display:none">
                <div class="mb-3">
                    <label for="autoservice_name" class="form-label">Введите название сервисного центра</label>
                    <input type="text" placeholder="Название сервисного центра" name="name_autoservice"
                        class="form-control" id="autoservice_name" />
                </div>
                <div class="mb-3">
                    <label for="document" class="form-label">Прикрепите документ(в формате pdf)</label>
                    <input type="file" accept="application/pdf" name="document" class="form-control" id="document" />
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" placeholder="password" name="password" class="form-control" id="password" />
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Подтверждение пароля</label>
                <input type="password" placeholder="password" name="password_confirm" class="form-control"
                    id="password_confirm" />
            </div>
            
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Введите код подтверждения" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary" type="button" href="/careauto.ru/vendor/send_email.php" id="button-addon2">Отправить код</button>
            </div>

            <!-- <form action="/careauto.ru/vendor/send_email.php" method="post" class="row g-2">
                <div class="col-auto">
                    <label for="input_conf_code" class="visually-hidden">Password</label>
                    <input type="text" class="form-control" id="input_conf_code" placeholder="код подтверждения">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">Отправить код</button>
                </div>
            </form> -->

            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
        <p>У вас уже есть аккаунт? - <a href="/careauto.ru/authoriz_page.php">Авторизируйтесь</a>
        </p>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<p><div class="alert alert-warning" role="alert">
            ' . $_SESSION['message'] . '</div></p>';
        }
        unset($_SESSION['message']);
        ?>
    </div>


</body>

</html>

<!-- Registration page -->

