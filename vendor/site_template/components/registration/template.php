<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/registration/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/registration/style.css">

<?php //на странице регистрации может находиться только неавторизованный пользователь
if (!empty($_SESSION['user'])) {
    header('Location: /');
}
if (!empty($_SESSION['new_user']['email'])) {
    unset($_SESSION['new_user']);
}
?>

<div class="container central column content">
    <h1>Регистрация</h1>
    <form id=client class="radio-blocks" action="/vendor/site_template/components/registration/signup.php" method="post" 
        enctype="multipart/form-data">
        <p>Выберите тип пользователя</p>
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" value="client" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                checked>
            <label class="btn btn-outline-primary" for="btnradio1">Автовладелец</label>

            <input type="radio" value="autoservice" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
        </div>
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
                <input type="text" required placeholder="Фамилия Имя Отчество" name="name_client" class="form-control"
                    id="name_client" />
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Выберите город</label>
                <select required name="city_id" class="form-select" aria-label="Default select example" id="city">
                    <option value="" disabled selected>Выберите город</option>
                    <?php //вывод списка городов
                        $arResult = city_list();
                        foreach ($arResult['CITIES'] as $city_id => $arCity) {
                            ?>
                                <option value="<?=$city_id?>"><?=$arCity['NAME']?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" required placeholder="Пароль от 5 до 20 символов" name="password"
                    class="form-control" id="password" pattern=".{5,20}" />
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Подтверждение пароля</label>
                <input type="password" required placeholder="Пароль подтверждения"
                    name="password_confirm" class="form-control" id="password_confirm" pattern=".{5,20}" />
            </div>
        </div>
        <button class="btn btn-primary" id="reg_button" name="reg_button" value="client"
            type="submit">Зарегистрироваться</button>
    </form>

    <form id="autoservice" class="radio-blocks" style="display:none" action="/vendor/site_template/components/registration/signup.php" 
        method="post" enctype="multipart/form-data">
        <p>Выберите тип пользователя</p>
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" value="client" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio1">Автовладелец</label>

            <input type="radio" value="autoservice" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                checked>
            <label class="btn btn-outline-primary" for="btnradio2">Сервисный центр</label>
        </div>
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
                <label for="city" class="form-label">Выберите город</label>
                <select required name="city_id" class="form-select" aria-label="Default select example" id="city">
                    <option value="" disabled selected>Выберите город</option>
                    <?php //вывод списка городов
                    foreach ($arResult['CITIES'] as $city_id => $arCity) {
                    ?>
                    <option value="<?= $city_id ?>"><?= $arCity['NAME'] ?></option>
                    <?php
                    }
                    ?>
                </select>
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
        </div>
        <button class="btn btn-primary" id="reg_button1" name="reg_button" value="autoservice"
            type="submit">Зарегистрироваться</button>
    </form>
    <p>У вас уже есть аккаунт? - <a href="/authorization/">Авторизируйтесь</a></p>
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