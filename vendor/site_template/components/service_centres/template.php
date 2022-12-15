<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/service_centres/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/service_centres/style.css">

<?php 
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
    getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>

<h1 class="container">Сервисные центры</h1>
<div class="container central">
    <div class="panel search" id="<?=$_SESSION['user']['id']?>">
        <h3>Параметры поиска</h3>
        <div class="autoservices_area with_btn">
            <div class="mb-3">
                <label class="form-label" for="autoserv_name">Название сервисного центра</label>
                <input class="form-control" id="autoserv_name" type="text" placeholder="Название сервисного центра"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="city">Город</label>
                <select class="form-select" id="city" name="city" aria-label="Default select example">
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
            <div class="multiselect mb-3">
                <label class="form-label" for="categories">Категории услуг</label>
                <div class="form-select selectBox" id="show_categories" onclick="showCheckboxes(this)">
                    <option>Выбрано категорий услуг: 0</option>
                </div>
                <div class="checkboxes" id="categories">
                    <?php //вывод категорий услуг
                    $categories = get_category_list();
                    foreach ($categories as $key => $value) {
                            echo '<label for="category_' . $key . '">
                                <input type="checkbox" id="category_' . $key . '" onclick="getServicesById(this)"/>' . $value . '</label>';
                    }?>
                </div>
            </div>
            <div class="multiselect mb-3">
                <label class="form-label" for="services">Услуги</label>
                <div class="form-select selectBox" id="show_services" onclick="showCheckboxes(this)">
                    <option>Выбрано услуг: 0</option>
                </div>
                <div class="checkboxes" id="services"></div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="autos">Ваш автомобиль</label>
                <select class="form-select" id="autos" name="autos" aria-label="Default select example" required>
                    <option value="" disabled selected>Выберите ваш автомобиль</option>
                    <?php //вывод списка автомобилей
                        $autos = getAutosById($_SESSION['user']['id']);
                        foreach ($autos as $auto_id => $ar_auto_info) {
                            ?>
                                <option value="<?=$auto_id?>"><?=$ar_auto_info['brand'] . ' ' . $ar_auto_info['model']?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <button class="btn btn-primary" id="start_add_automobile" type="button" onclick="searchAutoservices(this)">Поиск</button>
    </div>

    <div class="panel" id="search_autoservices">
        <h3>Сервисные центры в городе <?=getCityNameById($_SESSION['user']['city_id'])?></h3>
        <?php 
        /*<div class="btn_div">
            <label class="form-label" for="sort">Сортировка:</label>
             <select class="form-select" id="sort" name="sort" aria-label="Default select example" onchange="setAutoservSort(this)">
                <option value="0" selected>По соответствию фильтру</option>
                <option value="1">По возрастанию цены</option>
                <option value="2">По убыванию цены</option>
            </select> 
        </div>*/
        ?>
        <div class="autoservices_area">
            <?php //первоначальный вывод всех автосервисов в городе автовладельца
                $autoservices = getAutoservicesByParameters(['city' => $_SESSION['user']['city_id']]);
                if ($autoservices != NULL) {
                    foreach ($autoservices as $autoservice) {
                        foreach ($autoservice as &$value) {
                            if ($value == NULL) {
                                $value = '-';
                            }
                        }
                        echo '<div class="plate" id="autoservice_id_' . $autoservice['id'] . '" onclick="getAutoserviceInfo(this)">                    
                                <h3>' . $autoservice['name'] . '</h3>
                                <div class="central">
                                    <div class="text_list name">
                                        <p>Cтоимость услуг (р)</p><p>Телефон</p><p>Адрес</p>
                                    </div>
                                    <div class="text_list value">
                                        <p>' . $autoservice['price'] . '</p>
                                        <p>' . $autoservice['phone'] . '</p>
                                        <p>' . $autoservice['address'] . '</p>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo 'По вашему запросу ничего не найдено';
                }
            ?>
        </div>
    </div>
    
    <div id="current_autoservice">
        <div class="panel show_autoservice" id="<?=$autoservices[0]['id']?>">
        <?php //вывод информации об автосервисе из первоначального перечня
        if ($autoservices != NULL) {
            $autoservice_info = getAutoserviceInfoById($autoservices[0]['id']);
            foreach ($autoservice_info as &$value) {
                if ($value == NULL) {
                    $value = '-';
                }
            }
            echo '<h3>' . $autoservice_info['name'] . '</h3>
                <div class="autoservices_area with_btn">
                <p class="name">Описание</p><p class="value">' . $autoservice_info['text'] . '</p>
                <p class="name">Фотографии</p>';
                $ar_photos = getPhotosArray($autoservices[0]['id']);
                if (empty($ar_photos)) {
                    echo '<p id="photos_p" style="display: block">Отсутствуют</p>
                    <div class="photos" style="display: none">
                        <img class="major_photo" id="photo_main" src="" alt="">
                        <img class="minor_photo" id="photo_0" src="" alt="" onclick="gallery(this)">
                    </div>';
                } else {
                    echo '<p id="photos_p" style="display: none">Отсутствуют</p>
                    <div class="photos" style="display: block">';
                    $ar_name_photos = getPhotosNames($autoservices[0]['id']);
                    echo '<img class="major_photo" id="photo_main" src="' . $ar_photos[0] . '" alt="' . $ar_name_photos[0] . '">';
                    for ($photo_number = 0; $photo_number < count($ar_photos); $photo_number++){
                        echo '<img class="minor_photo" id="photo_' . $photo_number . '" src="' . $ar_photos[$photo_number] . '" alt="' . $ar_name_photos[$photo_number] . '" onclick="gallery(this)">';
                    }
                    echo '</div>';
                }
            echo '<p class="name">Телефон</p><p class="value">' . $autoservice_info['phone'] . '</p>
                    <p class="name">Адрес</p><p class="value">' . $autoservice_info['address'] . '</p>
            <div  class="mb-3">
                <label class="form-label name" for="autoserv_services">Обслуживаемые марки автомобилей</label>
                <p id="autoservice_brands_p" style="display: none">Перечень марок не указан</p>
                <div class="central" id="autoservice_brands">';
            if (!empty($autoservice_info['brand_list'])) {
                foreach ($autoservice_info['brand_list'] as $brand) {
                    echo '<p id="' . $brand['id'] . '">' . $brand['name'] . '</p>';
                }
            }  
            echo '</div>
                </div>
                <div class="mb-3">
                    <label class="form-label name" for="autoserv_services">Список услуг сервисного центра</label>
                    <p id="autoservice_services_p" style="display: none">Перечень марок не указан</p>
                    <select class="form-select" id="autoserv_services" name="autoserv_services" aria-label="Default select example" onchange="getServiceInfo(this)">';
                    foreach ($autoservice_info['services_id'] as $service_id) {
                        $service_info = getServiceInfo($autoservice_info['id'], $service_id);
                        echo '<option value="' . $service_id . '">' . $service_info['name'] . '</option>';
                    }
                    $service_info = getServiceInfo($autoservice_info['id'], $autoservice_info['services_id'][0]);
                    foreach ($service_info as &$value) {
                        if ($value == NULL) {
                            $value = '-';
                        }
                    }
            echo '</select>
                    <div class="central" id="service_info">
                        <label class="form-label name" for="autoserv_services">Категория</label>
                        <p>' . $service_info['category'] . '</p>
                        <label class="form-label name" for="autoserv_services">Стоимость</label>
                        <p>' . $service_info['price'] . '</p>
                        <label class="form-label name" for="autoserv_services">Сертификация</label>';
                        if ($service_info['certification'] == '-') {
                            echo '<p style="display: block">Отсутствует</p>
                                <a target="_blank" href="' . $service_info['certification'] . '" style="display: none">' . mb_substr($service_info['certification'], 1 + strpos($service_info['certification'], '-')) . '</a>';
                        } else {
                            echo '<p style="display: none">Отсутствует</p>
                            <a target="_blank" href="' . $service_info['certification'] . '" style="display: block">' . mb_substr($service_info['certification'], 1 + strpos($service_info['certification'], '-')) . '</a>';                    
                        }
                        echo '<label class="form-label name" for="autoserv_services">Описание</label>
                            <p>' . $service_info['text'] . '</p>
                        </div>
                    </div>
                </div>
                <div class="btn_div">
                    <button class="btn btn-primary" id="create_application" type="button" onclick="createApplication(this)">Сформировать заявку</button>
                    <button onclick="showcomplaint(this)" value="' . $autoservice_info['id'] . '" id="show_complaint_' . $autoservice_info['id'] . '"name="show_complaint" class="btn btn-outline-danger" type="button">Пожаловаться</button>
                </div>';
        } else {
            echo 'Здесь мог бы быть Ваш автосервис</div>';
        }
        ?>
        
        </div> 
    </div>
</div>