<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/style.css">

<?php //на странице профиля может находиться только авторизованный пользователь
if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}
?>
<?php
        if ($_SESSION['user']['user_type'] == 'client') {
            echo '<h1 class="container">Личный кабинет</h1>';
        } elseif ($_SESSION['user']['user_type'] == 'autoservice') {
            echo '<h1 class="container">Личный кабинет</h1>';
        }
?>
<div class="container central">
    <div>
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_personal_data.php" method="post">
            <h3>Контактная информация</h3>
            <?php
            if ($_SESSION['user']['user_type'] == 'client') {
                $amount_cars = count(cars_list($_SESSION['user']['id']));
                if ($amount_cars == 0) {
                    echo '<h5>Исследователь</h5>';
                } elseif ($amount_cars == 1) {
                    echo '<h5>Уверенный автолюбитель</h5>';
                } elseif ($amount_cars == 2) {
                    echo '<h5>Опытный водитель</h5>';
                } else {
                    echo '<h5>Коллекционер автомобилей</h5>';
                }
            } elseif ($_SESSION['user']['user_type'] == 'autoservice') {
                echo '<h5>Профиль личного кабинета автовладельца</h5>';
            }
            ?>
            <div class="mb-3">
                <label class="form-label" for="name"><?php if ($_SESSION['user']['user_type'] == 'client') {echo 'ФИО';} 
                    else {echo 'Название';}?></label>
                <input class="form-control" id="name" name="name" type="text" value="<?=$_SESSION['user']['name']?>" placeholder="ФИО" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Адрес электронной почты</label>
                <input class="form-control" id="email" name="email" type="email" value="<?=$_SESSION['user']['email']?>" placeholder="Эл. почта" 
                    aria-describedby="emailHelp" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" id="phone" name="phone" type="text" value="<?=$_SESSION['user']['phone']?>" placeholder="Номер телефона" 
                    data-phone-pattern required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="city">Город</label>
                <select class="form-select" id="city_id" name="city_id" aria-label="Default select example">
                    <option value=<?php if ($_SESSION['user']['city_id'] !== null) echo '"' . $_SESSION['user']['city_id'] . '"'; 
                                        else echo '"" disabled ';?>selected>
                        <?php if ($_SESSION['user']['city_id'] !== null) echo city_id_name($_SESSION['user']['city_id']); 
                                else echo 'Выберите город';?></option>
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
            <?php
            if ($_SESSION['user']['user_type'] == 'autoservice') {?>
                <div class="mb-3">
                    <label class="form-label" for="address">Адрес</label>
                    <input class="form-control" id="address" name="address" type="text" value="<?=address_name($_SESSION['user']['id'])?>" 
                        placeholder="Адрес сервисного центра"/>
                </div>
            <?php }?>
            <button class="btn btn-primary" id="change_data" name="change_data" type="submit">Сохранить</button>
        </form>
        <div>
            <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_password.php" method="post">
                <h3>Сменить пароль</h3>
                <div class="mb-3">
                    <label class="form-label" for="password">Текущий пароль</label>
                    <input class="form-control" id="current_password" name="current_password" type="password" placeholder="Текущий пароль" pattern=".{5,20}" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Новый пароль</label>
                    <input class="form-control" id="new_password" name="new_password" type="password" placeholder="Новый пароль" pattern=".{5,20}" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Подтверждение пароля</label>
                    <input class="form-control" id="conf_new_password" name="conf_new_password" type="password" placeholder="Подтверждение пароля" pattern=".{5,20}" required/>
                </div>
                <button class="btn btn-primary" id="change_password" name="change_password" type="submit">Сохранить</button>
            </form>
            <div class="panel" style="height: auto">
                <h4>Удалить аккаунт</h4>
                <button class="btn btn-outline-danger" id="del_btn" name="del_btn" type="button">Удалить</button>
            </div>

        </div>
    </div>
    <p class="message_window">
        <?php //блок вывода сообщений
        if (isset($_SESSION['message'])) {
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
        unset($_SESSION['message']);
        ?>
    </p>
</div>