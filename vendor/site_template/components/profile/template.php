<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/style.css">

<?php //на странице профиля может находиться только авторизованный пользователь
if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}
getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>
<h1 class="container">Настройки</h1>
<div class="container central">
    <div class="navigation">
        <?php 
        if ($_SESSION['user']['user_type'] == 'autoservice') { ?>
            <a class="btn btn-outline-primary" id="main_data_caption" name="main_data_caption" type="button" href="#main_data">Основная информация</a>
            <a class="btn btn-outline-primary" id="contacts_caption" name="contacts_caption" type="button" href="#contacts">Контакты</a>
            <a class="btn btn-outline-primary" id="requisites_caption" name="requisites_caption" type="button" href="#requisites">Реквизиты</a>
            <a class="btn btn-outline-primary" id="change_password_caption" name="change_password_caption" type="button" href="#change_password">Смена пароля</a>
            <a class="btn btn-outline-primary" id="delete_account_caption" name="delete_account_caption" type="button" href="#delete_account">Удаление аккаунта</a>
        <?php } else { ?>
            <a class="btn btn-outline-primary" id="main_data_caption" name="main_data_caption" type="button" href="#main_data">Основная информация</a>
            <a class="btn btn-outline-primary" id="change_password_caption" name="change_password_caption" type="button" href="#change_password">Смена пароля</a>
            <a class="btn btn-outline-primary" id="delete_account_caption" name="delete_account_caption" type="button" href="#delete_account">Удаление аккаунта</a>
        <?php 
        }
        ?>
        <p class="message_window">
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
            if ($_SESSION['user']['user_type'] == 'autoservice') {
                $infoamount = getAutoserviceServAndBrandAmountById($_SESSION['user']['id']);
                if ($infoamount['brands'] == 0 || $infoamount['services'] == 0) {
                    echo '<p><div class="alert alert-warning" role="alert">
                    Сервисные центры с пустым перечнем обслуживаемых марок и предоставляемых услуг клиентам не видны!</div></p>';
                }
            }
            ?>
        </p>
    </div>

    <div>
        <?php if ($_SESSION['user']['user_type'] == 'autoservice') { $autoservice = getAllUserInfo($_SESSION['user']['id'], 'autoservice');?>
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_main_data.php" method="post" enctype="multipart/form-data">
            <h3 id="main_data">Основная информация</h3>
            <?php
                if ($_SESSION['user']['user_type'] == 'autoservice') {
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
                <textarea class="form-control" id="description" name="description" type="textarea" maxlength="300" placeholder="Описание Вашего сервисного центра"><?=$autoservice['text']?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="photos">Фотографии (до 5 шт; рекомендуемый формат - 16:9)</label>
                <input class="form-control" id="photos" name="photos[]" type="file" accept="image/jpeg" multiple/>
                <div class="photos">  
                    <?php //фотографии
                    $ar_photos = getPhotosArray($_SESSION['user']['id']);                
                    if (!empty($ar_photos)) {
                        $ar_name_photos = getPhotosNames($_SESSION['user']['id']);
                        echo '<img class="major_photo" id="photo_main" src="' . $ar_photos[0] . '" alt="' . $ar_name_photos[0] . '">';
                        for ($photo_number = 0; $photo_number < count($ar_photos); $photo_number++){
                            echo '<img class="minor_photo" id="photo_' . $photo_number . '" src="' . $ar_photos[$photo_number] . '" alt="' . $ar_name_photos[$photo_number] . '" onclick="gallery(this)">';
                        }
                    }?>
                </div>
                <?php
                    if (!empty($ar_photos)) { //вывод кнопок
                        echo '<div class="btn_div">
                                <button class="btn btn-secondary" id="del_photo" name="del_photo" type="button">Удалить фото</button>
                                <button class="btn btn-secondary" id="del_all_photos" name="del_all_photos" type="button">Удалить все фото</button>
                            </div>';
                    }
                ?>
            </div>
            <div class="multiselect mb-3">
                <label class="form-label" for="brands">Обслуживаемые марки авто</label>
                <div class="form-select selectBox" onclick="showCheckboxes(this)">
                    <option>Выбрано марок авто: <?php $autoserv_brands = getAutoserviceBrands($_SESSION['user']['id']); 
                    if ($autoserv_brands == null) {
                        $autoserv_brands = [];
                    }
                    echo count($autoserv_brands);?></option>
                </div>
                <div class="checkboxes">
                    <?php //вывод обслуживаемых марок
                    $brands = brands();
                    foreach ($brands as $brand) {
                        if (in_array($brand, $autoserv_brands)) {
                            echo '<label for="' . $brand['id'] . '">
                                <input type="checkbox" id="' . $brand['id'] . '" onclick="set_brand(this)" checked/>' . $brand['name'] . '</label>';
                        } else {
                            echo '<label for="' . $brand['id'] . '">
                                <input type="checkbox" id="' . $brand['id'] . '" onclick="set_brand(this)"/>' . $brand['name'] . '</label>';
                        }
                    }?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="doc">Документ: <?php $doc_path = doc_path($_SESSION['user']['id']);
                    echo '<a target="_blank" href="' . doc_path($_SESSION['user']['id']) . '">' . substr($doc_path, stripos($doc_path, '-')+1) . '</a>';?></label>                    
            </div>
            <button class="btn btn-primary" id="change_main_data" name="change_main_data" type="submit">Сохранить</button>
        </form>
        <?php } ?>
        
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_hybrid_data.php" method="post">
            <?php
            if ($_SESSION['user']['user_type'] == 'client') {
                echo '<h3 id="main_data">Основная информация</h3>';                        
                /*$amount_cars = count(cars_list($_SESSION['user']['id']));
                if ($amount_cars == 0) {
                    echo '<h5>Исследователь</h5>';
                } elseif ($amount_cars == 1) {
                    echo '<h5>Уверенный автолюбитель</h5>';
                } elseif ($amount_cars == 2) {
                    echo '<h5>Опытный водитель</h5>';
                } else {
                    echo '<h5>Коллекционер автомобилей</h5>';
                }*/
                echo '<div class="mb-3">
                            <label class="form-label" for="name">ФИО</label>
                            <input class="form-control" id="name" name="name" type="text" value="' . $_SESSION['user']['name'] . 
                                '"placeholder="Название Вашего сервисного центра" required/>
                        </div>';
                main_contacts();
            } else {
                echo '<h3 id="contacts">Контакты</h3>';
                main_contacts();
                echo '<div class="mb-3">
                        <label class="form-label" for="address">Адрес</label>
                        <input class="form-control" id="address" name="address" type="text" value="' . address_name($_SESSION['user']['id']) . 
                            '"placeholder="Адрес сервисного центра"/>
                    </div>';
            }?>
            <button class="btn btn-primary" id="change_hybrid_data" name="change_hybrid_data" type="submit">Сохранить</button>
        </form>
        
        <?php if ($_SESSION['user']['user_type'] == 'autoservice') {?>
        <form class="panel" action="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/change_requisites.php" method="post">
            <h3 id="requisites">Реквизиты</h3>
            <div class="mb-3">
                <label class="form-label" for="inn">ИНН</label>
                <input class="form-control" id="inn" name="inn" type="text" value="<?php
                    if (!empty(getRequisitesInfo($_SESSION['user']['id']))) {echo getRequisitesInfo($_SESSION['user']['id'])['inn'];}?>" 
                    placeholder="ИНН организации" maxlength="10" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="kpp">КПП</label>
                <input class="form-control" id="kpp" name="kpp" type="text" value="<?php 
                    if (!empty(getRequisitesInfo($_SESSION['user']['id']))) {echo getRequisitesInfo($_SESSION['user']['id'])['kpp'];}?>" 
                    placeholder="КПП организации" maxlength="9"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="bik">БИК</label>
                <input class="form-control" id="bik" name="bik" type="text" value="<?php 
                    if (!empty(getRequisitesInfo($_SESSION['user']['id']))) {echo getRequisitesInfo($_SESSION['user']['id'])['bik'];}?>" 
                    placeholder="БИК организации" maxlength="9" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="check_acc">Расчётный счёт</label>
                <input class="form-control" id="check_acc" name="check_acc" type="text" value="<?php 
                    if (!empty(getRequisitesInfo($_SESSION['user']['id']))) {echo getRequisitesInfo($_SESSION['user']['id'])['check_acc'];}?>" 
                    placeholder="Рассчётный счёт организации" maxlength="20" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="corr_acc">Корреспондентский счёт</label>
                <input class="form-control" id="corr_acc" name="corr_acc" type="text" value="<?php 
                    if (!empty(getRequisitesInfo($_SESSION['user']['id']))) {echo getRequisitesInfo($_SESSION['user']['id'])['corr_acc'];}?>" 
                    placeholder="Корреспонденсткий счёт организации" maxlength="20"/>
            </div>
            <div class="btn_div">
                <button class="btn btn-primary" id="change_requisites" name="change_requisites" type="submit">Сохранить</button>
                <button class="btn btn-secondary" id="reset_requisites" name="reset_requisites" type="button">Очистить</button>
            </div>
        </form>
        <?php } ?>

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

        <div class="panel" style="height: auto">
            <h3 id="delete_account">Удаление аккаунта</h3>
            <button class="btn btn-outline-danger" id="del_btn" name="del_btn" type="button">Удалить</button>
        </div>
    </div>    
</div>