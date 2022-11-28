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
            <input class="form-control" id="autoserv_name"type="text" placeholder="Название сервисного центра"/>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Город</label>
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
        <div class="multiselect mb-3">
            <label class="form-label" for="categories">Категории услуг</label>
            <div class="form-select selectBox" onclick="showCheckboxes()">
                <option>Выбрано категорий услуг: </option>
            </div>
            <div id="checkboxes">
                <?php //вывод категорий услуг
                $brands = brands();
                foreach ($brands as $brand) {                    
                        echo '<label for="' . $brand['id'] . '">
                            <input type="checkbox" id="' . $brand['id'] . '" onclick="set_brand(this)"/>' . $brand['name'] . '</label>';
                }?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="services">Услуги</label>
            <select class="form-select" id="services" name="services" aria-label="Default select example" required>
                <option value="" disabled selected>Выберите услуги</option>
            </select>
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