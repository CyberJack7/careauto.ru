<?php
session_start();
require_once 'vendor/connect.php';
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
    // переключение кнопок-форм
    $(function() {
        $("#" + $(".radio:checked").val()).show();
        $(".btn-check").change(function() {
            $(".radio-blocks").hide();
            $("#" + $(this).val()).show();
        });
    });
    </script>

    <script type="text/javascript">
    // маскка для телефона
    $(function() {
        $('[data-phone-pattern]').on('input blur focus', (e) => {
            var el = e.target,
                clearVal = $(el).data('phoneClear'),
                pattern = $(el).data('phonePattern'),
                matrix_def = "+7(___) ___-__-__",
                matrix = pattern ? pattern : matrix_def,
                i = 0,
                def = matrix.replace(/\D/g, ""),
                val = $(el).val().replace(/\D/g, "");
            if (clearVal !== 'false' && e.type === 'blur') {
                if (val.length < matrix.match(/([\_\d])/g).length) {
                    $(el).val('');
                    return;
                }
            }
            if (def.length >= val.length) val = def;
            $(el).val(matrix.replace(/./g, function(a) {
                return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val
                    .length ? "" : a;
            }));
        });
    });
    </script>
    <!-- подсветка полей -->
    <script type="text/javascript">
    $('document').ready(function() {
        $(':submit').on('click', function() {
            $('input[required]').addClass('req');
            $('select[required]').addClass('req');
        });
    });
    </script>
    <script type="text/javascript">
    $('document').ready(function() {
        $(':radio').on('click', function() {
            $('input[required]').removeClass('req');
            $('select[required]').removeClass('req');
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
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" value="client" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                checked>
            <label class="btn btn-outline-primary" for="btnradio1">Клиент</label>

            <input type="radio" value="autoservice" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
        </div>
        <form id=client class="radio-blocks" action="/careauto.ru/vendor/signup.php" method="post"
            enctype="multipart/form-data">
            <div class="form_box">

                <div class="mb-3">
                    <label for="email_client" class="form-label">Адрес электронной почты</label>
                    <input type="email" required placeholder="email" name="email" class="form-control"
                        id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
                <div class="mb-3">
                    <label for="phone_client" class="form-label">Укажите ваш номер телефона</label>
                    <input type="text" data-phone-pattern required placeholder="Номер телефона" name="phone"
                        class="form-control" id="phone_client" />
                </div>
                <div class="mb-3">
                    <label for="name_client" class="form-label">ФИО</label>
                    <input type="text" required placeholder="Фамилия Имя Отчество" name="name_client"
                        class="form-control" id="name_client" />
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Выберите город</label>
                    <select required name="city_id" class="form-select" aria-label="Default select example" id="city">
                        <option value="" disabled selected>Выберите город</option>
                        <?php
                        $sql = "SELECT city_id, name_city FROM Public.city ORDER BY name_city asc";
                        $city = $pdo->query($sql);
                        while ($res_city = $city->fetch()) {
                            printf("<option value='%s'>%s</option>", $res_city["city_id"], $res_city["name_city"]);
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" required placeholder="password  5 to 20 characters" name="password"
                        class="form-control" id="password" pattern=".{5,20}" />
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Подтверждение пароля</label>
                    <input type="password" required placeholder="password confirm  5 to 20 characters"
                        name="password_confirm" class="form-control" id="password_confirm" pattern=".{5,20}" />
                </div>

                <button id="reg_button" name="reg_button" value="client" type="submit"
                    class="btn btn-primary">Зарегистрироваться</button>
            </div>
        </form>
        <form id="autoservice" class="radio-blocks" style="display:none" action="/careauto.ru/vendor/signup.php"
            method="post" enctype="multipart/form-data">
            <div class="form_box">
                <div class="mb-3">
                    <label for="email_client" class="form-label">Адрес электронной почты</label>
                    <input type="email" required placeholder="email" name="email" class="form-control"
                        id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
                <div class="mb-3">
                    <label for="phone_client" class="form-label">Укажите ваш номер телефона</label>
                    <input type="text" data-phone-pattern required placeholder="Номер телефона" name="phone"
                        class="form-control" id="phone_client" />
                </div>
                <div class="mb-3">
                    <label for="autoservice_name" class="form-label">Введите название сервисного центра</label>
                    <input type="text" required placeholder="Название сервисного центра" name="name_autoservice"
                        class="form-control" id="autoservice_name" />
                </div>
                <div class="mb-3">
                    <label for="document" class="form-label">Прикрепите документ(в формате pdf)</label>
                    <input type="file" required accept="application/pdf" name="document" class="form-control"
                        id="document" />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" required placeholder="password 5 to 20 characters" name="password"
                        class="form-control" id="password" pattern=".{5,20}" />
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Подтверждение пароля</label>
                    <input type="password" required placeholder="password confirm 5 to 20 characters"
                        name="password_confirm" class="form-control" id="password_confirm" pattern=".{5,20}" />
                </div>
                <button id="reg_button1" name="reg_button" value="autoservice" type="submit"
                    class="btn btn-primary">Зарегистрироваться</button>
            </div>
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