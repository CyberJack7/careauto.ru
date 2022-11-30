<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/service_centres/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/service_centres/style.css">

<?php 
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
?>

<h1 class="container">Сервисные центры</h1>
<div class="container central">
    <div class="panel search">
        <div class="mb-3">
            <label class="form-label" for="autoserv_name">Название сервисного центра</label>
            <input class="form-control" id="autoserv_name" type="text" placeholder="Название сервисного центра"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="city">Город</label>
            <select class="form-select" id="city" name="city_id" aria-label="Default select example">
                <option disabled selected>Выберите город</option>
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
                        echo '<label for="' . $key . '">
                            <input type="checkbox" id="' . $key . '" onclick="getServicesById(this)"/>' . $value . '</label>';
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
            <select class="form-select" id="city" name="auto_id" aria-label="Default select example" required>
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
        
        <button class="btn btn-primary" id="start_add_automobile" type="button">Поиск</button>
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
            ?>
        </p>
    </div>

    <div class="panel" id="search_autoservices">

    </div>

    <div id="current_autoservice">
        <div class="panel" id="show_autoservice">
    
        </div>
    
        <div class="panel" id="send_application" style="display: none">
    
        </div>        
    </div>
    

</div>