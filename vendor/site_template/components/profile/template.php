<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/style.css">

<?php //на странице профиля может находиться только авторизованный пользователь
if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}
?>
<h1 class="container">Настройки</h1>
<div class="container central content">
    <div class="navigation_profile">
        <a class="btn btn-outline-primary" id="main_data_caption" name="main_data_caption" type="button" href="#main_data">Основная информация</a>
        <a class="btn btn-outline-primary" id="contacts_caption" name="contacts_caption" type="button" href="#contacts">Контакты</a>
        <a class="btn btn-outline-primary" id="requisites_caption" name="requisites_caption" type="button" href="#requisites">Реквизиты</a>
        <a class="btn btn-outline-primary" id="change_password_caption" name="change_password_caption" type="button" href="#change_password">Смена пароля</a>
        <a class="btn btn-outline-primary" id="delete_account_caption" name="delete_account_caption" type="button" href="#delete_account">Удаление аккаунта</a>
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
    <div>
        <?php if($_SESSION['user']['user_type'] == 'autoservice') {?>
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_personal_data.php" method="post">
            <h3 id="main_data">Основная информация</h3>
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
                    echo '<h5>Сервисный центр</h5>';
                }
            ?>
            <div class="mb-3">
                <label class="form-label" for="name">Название</label>
                <input class="form-control" id="name" name="name" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Описание</label>
                <textarea class="form-control" id="description" name="description" type="textarea" value="" placeholder="Описание Вашего сервисного центра"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="images">Фотографии</label>
                <input class="form-control" id="images" name="images" type="file" accept="image/jpeg"/>
            </div>
            <form>
                <div class="multiselect">
                    <div class="selectBox" onclick="showCheckboxes()">
                        <select>
                            <option>Select an option</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="checkboxes">
                        <label for="one">
                            <input type="checkbox" id="one" />First checkbox</label>
                        <label for="two">
                            <input type="checkbox" id="two" />Second checkbox</label>
                        <label for="three">
                            <input type="checkbox" id="three" />Third checkbox</label>
                            <label for="three">
                            <input type="checkbox" id="three" />Third checkbox</label><label for="three">
                            <input type="checkbox" id="three" />Third checkbox</label><label for="three">
                            <input type="checkbox" id="three" />Third checkbox</label><label for="three">
                            <input type="checkbox" id="three" />Third checkbox</label> 
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="doc">Документ: <?php $doc_path = doc_path($_SESSION['user']['id']);
                        echo '<a target="_blank" href="/uploads/regular/1668702142-Doc.pdf">' . substr($doc_path, stripos($doc_path, '-')+1) . '</a>';?></label>                    
                </div>
                <button class="btn btn-primary" id="change_main_data" name="change_main_data" type="submit">Сохранить</button>
            </form>
        </form>
        
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_personal_data.php" method="post">
            <h3 id="contacts">Контакты</h3>
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
            <div class="mb-3">
                <label class="form-label" for="address">Адрес</label>
                <input class="form-control" id="address" name="address" type="text" value="<?=address_name($_SESSION['user']['id'])?>" 
                    placeholder="Адрес сервисного центра"/>
            </div>
            <button class="btn btn-primary" id="change_contacts" name="change_contacts" type="submit">Сохранить</button>
        </form>

        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_requisites.php" method="post">
            <h3 id="requisites">Реквизиты</h3>
            <div class="mb-3">
                <label class="form-label" for="inn">ИНН</label>
                <input class="form-control" id="inn" name="inn" type="text" value="" placeholder="ИНН организации"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="kpp">КПП</label>
                <input class="form-control" id="kpp" name="kpp" type="text" value="" placeholder="КПП организации"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="bik">БИК</label>
                <input class="form-control" id="bik" name="bik" type="text" value="" placeholder="БИК организации"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="check_acc">Рассчётный адрес</label>
                <input class="form-control" id="check_acc" name="check_acc" type="text" value="" placeholder="Рассчётный счёт организации"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="corr_acc">Корреспондентский адрес</label>
                <input class="form-control" id="corr_acc" name="corr_acc" type="text" value="" placeholder="Корреспонденсткий счёт организации"/>
            </div>
            <button class="btn btn-primary" id="change_requisites" name="change_requisites" type="submit">Сохранить</button>
        </form>

        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_password.php" method="post">
            <h3 id="change_password">Смена пароля</h3>
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
        <?php } elseif ($_SESSION['user']['user_type'] == 'client') {?>
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_personal_data.php" method="post">
            <h3>Контактная информация</h3>
<!--             
            
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" id="phone" name="phone" type="text" value="<?=$_SESSION['user']['phone']?>" placeholder="Номер телефона" 
                    data-phone-pattern required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" id="phone" name="phone" type="text" value="<?=$_SESSION['user']['phone']?>" placeholder="Номер телефона" 
                    data-phone-pattern required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" id="phone" name="phone" type="text" value="<?=$_SESSION['user']['phone']?>" placeholder="Номер телефона" 
                    data-phone-pattern required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" id="phone" name="phone" type="text" value="<?=$_SESSION['user']['phone']?>" placeholder="Номер телефона" 
                    data-phone-pattern required/>
            </div>
            <?php
            if ($_SESSION['user']['user_type'] == 'autoservice') {?>
                <div class="mb-3">
                    <label class="form-label" for="address">Адрес</label>
                    <input class="form-control" id="address" name="address" type="text" value="<?=address_name($_SESSION['user']['id'])?>" 
                        placeholder="Адрес сервисного центра"/>
                </div> -->
            <?php }?>
            <button class="btn btn-primary" id="change_data" name="change_data" type="submit">Сохранить</button>
        </form> <?php } ?>
        <div class="panel" style="height: auto">
            <h3 id="delete_account">Удаление аккаунта</h3>
            <button class="btn btn-outline-danger" id="del_btn" name="del_btn" type="button">Удалить</button>
        </div>
    </div>    
</div>