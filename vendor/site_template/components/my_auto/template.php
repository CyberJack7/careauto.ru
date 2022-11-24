<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/my_auto/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/my_auto/style.css">

<?php 
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
?>

<h1 class="container">Мои авто</h1>
<div class="container central">
    <div class="navigation">
        <div class="list-group" id="list-example">
            <?php
            $cars = cars_list($_SESSION['user']['id']);
            $count = 0;
            if (empty($cars)) { //Если авто нет
                echo '<p class="message_window"><div class="alert alert-info" role="alert">Добавьте свой первый автомобиль!</div></p>';
            }
            foreach ($cars as $car) { 
                $count++;
                echo '<button class="list-group-item list-group-item-action" id="' . $car['id'] . '" onclick="showAuto(this)">'
                  . $car['brand'] . ' ' . $car['model'] . '</button>';
            }
            ?>
        </div>
        <button class="btn btn-primary" id="start_add_automobile" name="start_add_automobile" type="button">Добавить автомобиль</button>
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

    <div>
        <div class="panel" id="add_auto" style="display: none;"> <? //панель добавления автомобиля ?>
            <h3>Добавление автомобиля</h3>
            <div class="mb-3">
                <label class="form-label" for="brand">Марка</label>
                <select class="form-select" id="brand" name="brand" aria-label="Default select example" onchange="getBrandId(this)" required>
                    <option value="" disabled selected>Выберите марку</option>
                    <?php //вывод списка марок
                    $brands = brands();
                    foreach ($brands as $brand) { 
                     echo '<option value="' . $brand['id'] . '">' . $brand['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="model">Модель</label>
                <select class="form-select" id="model" name="model" aria-label="Default select example" required>
                    <option value="" disabled selected>Выберите модель</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="configuration">Комплектация</label>
                <input class="form-control" id="configuration" name="configuration" type="text" placeholder="Комплектация автомобиля"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="auto_year">Год выпуска</label>
                <input class="form-control" id="auto_year" name="auto_year" type="number" min="1886" max="<?=date('o')?>" placeholder="Год выпуска автомобиля"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="date_buy">Дата покупки</label>
                <input class="form-control" id="date_buy" name="date_buy" type="date" min="1886-01-29" max="<?=date('Y-m-d')?>" placeholder="Дата покупки автомобиля"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="mileage">Пробег</label>
                <input class="form-control" id="mileage" name="mileage" type="number" min="0" step="100" placeholder="Пробег"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="body">Кузов</label>
                <select class="form-select" id="body" name="body" aria-label="Default select example">
                    <option value="" disabled selected>Выберите тип кузова</option>
                    <?php //вывод списка кузовов
                    $bodies = bodies();
                    foreach ($bodies as $body) { 
                     echo '<option value="' . $body['id'] . '">' . $body['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="color">Цвет</label>
                <input class="form-control" id="color" name="color" type="text" placeholder="Цвет автомобиля"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine">Двигатель</label>
                <select class="form-select" id="engine" name="engine" aria-label="Default select example">
                    <option value="" disabled selected>Выберите тип двигателя</option>
                    <?php //вывод списка двигателей
                    $engines = engines();
                    foreach ($engines as $engine) { 
                     echo '<option value="' . $engine['id'] . '">' . $engine['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine_volume">Объём двигателя (л)</label>
                <input class="form-control" id="engine_volume" name="engine_volume" type="number" min="0.2" max="10" step="0.1" placeholder="Объём двигателя"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine_power">Мощность двигателя (л.с.)</label>
                <input class="form-control" id="engine_power" name="engine_power" type="number" min="1" max="2028" step="1" placeholder="Мощность двигателя"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="gearbox">Тип КПП</label>
                <select class="form-select" id="gearbox" name="gearbox" aria-label="Default select example">
                    <option value="" disabled selected>Выберите тип КПП</option>
                    <?php //вывод списка типов КПП
                    $gearboxes = gearboxes();
                    foreach ($gearboxes as $gearbox) { 
                     echo '<option value="' . $gearbox['id'] . '">' . $gearbox['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="drive">Привод</label>
                <select class="form-select" id="drive" name="drive" aria-label="Default select example">
                    <option value="" disabled selected>Выберите тип привода</option>
                    <?php //вывод списка типов КПП
                    $drives = drives();
                    foreach ($drives as $drive) { 
                     echo '<option value="' . $drive['id'] . '">' . $drive['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="PTS">ПТС</label>
                <input class="form-control" id="PTS" name="PTS" type="text" maxlength="15" placeholder="ПТС автомобиля" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="VIN">VIN</label>
                <input class="form-control" id="VIN" name="VIN" type="text" maxlength="17" placeholder="VIN номер автомобиля" required/>
            </div>
            <button class="btn btn-primary" id="add_automobile" name="add_automobile" type="button">Добавить</button>
        </div>

        <?php /*создание панелей информации о каждом автомобиле*/
            if (!empty($cars)) { //Если список автомобилей не пуст
                echo '<div class="panel" id="show_autos" style="display: block;">';
                $count = 0;
                foreach ($cars as $car) {                
                    $auto = getAutoInfoById($car['id']);
                    if ($count == 0) {
                        echo '<div class="show_auto" id="show_auto_' . $car['id'] . '" style="display: block;">';
                    } else {
                        echo '<div class="show_auto" id="show_auto_' . $car['id'] . '" style="display: none;">';
                    }
                        echo '<h3>' . getBrandNameById($auto['brand_id']) . ' ' . getModelNameById($auto['model_id']) . '</h3>
                                <div class="central">
                                    <div class="text_list name">
                                        <p>Комплектация</p><p>Год выпуска</p><p>Дата покупки</p><p>Пробег</p><p>Кузов</p>
                                        <p>Цвет</p><p>Двигатель</p><p>Коробка</p><p>Привод</p><p>ПТС</p><p>VIN</p>
                                    </div>
                                    <div class="text_list value">
                                        <p>' . $auto['configuration'] . '</p><p>' . $auto['auto_year'] . '</p>
                                        <p>' . $auto['date_buy'] . '</p><p>' . $auto['mileage'] . '</p>
                                        <p>' . getBodyNameById($auto['body_id']) . '</p><p>' . $auto['color'] . '</p>
                                        <p>' . getEngineNameById($auto['engine_id']) . '/' . $auto['engine_volume'] . 'л/' . $auto['engine_power'] . 'л.с.' . '</p>
                                        <p>' . getGearboxNameById($auto['gearbox_id']) . '</p><p>' . getDriveNameById($auto['drive_id']) . '</p>
                                        <p>' . $auto['pts'] . '</p><p>' . $auto['vin'] . '</p>
                                    </div>
                                </div>
                            </div>';
                    $count++;
                }
                echo '<div class="btn_div">
                        <button class="btn btn-primary" id="change_car_info" name="change_car_info" type="button">Редактировать</button>
                        <button class="btn btn-outline-danger" id="delete_car" name="delete_car" type="button">Удалить</button>
                    </div>
                </div>';
            }?>
    </div>

    <div class="panel" id="tires">
        <h3>Комплекты резины</h3>
        <?php /*создание панелей комплектов резины для каждого автомобиля*/
            if (!empty($cars)) { //Если список автомобилей не пуст
                $count = 0;
                foreach ($cars as $car) { //для каждого автомобиля
                    if ($count == 0) {
                        echo '<div class="show_tires" id="show_tires_' . $car['id'] . '" style="display: block;">';
                    } else {
                        echo '<div class="show_tires" id="show_tires_' . $car['id'] . '" style="display: none;">';
                    }
                    $tires_id_list = getTiresListById($car['id']);
                    foreach($tires_id_list as $tire_id) {
                        $tires = getTiresInfoById($tire_id);
                        echo '<div class="plate" onclick="showTires(this)">
                                <div class="btn_div">
                                    <h5>' . $tires['brand_tires'] . '</h5>
                                    <div>
                                        <img class="edit_img" id="tires_' . $tires['tires_id'] . '" src="/images/edit.png" onclick="editTires(this)">
                                        <img class="delete_img" id="tires_' . $tires['tires_id'] . '" src="/images/delete.png" onclick="deleteTires(this)">
                                    </div>
                                </div>
                                <div class="central" style="display: none;">
                                    <div class="text_list name">
                                        <p>Тип резины</p><p>Маркировка</p><p>Дата покупки</p>
                                    </div>
                                    <div class="text_list value">
                                        <p>' . getTiresTypeNameById($tires['tire_type_id']) . '</p>
                                        <p>' . $tires['marking'] . '</p>
                                        <p>' . $tires['date_buy'] . '</p>
                                    </div>
                                </div>
                            </div>';
                    }
                    $count++;
                    echo '</div>';
                }
            }?>
            <button class="btn btn-primary" id="show_add_tires" name="show_add_tires" type="button" onclick="showAddTires(this)">Добавить новый комплект</button>           
        
        
        <div id="add_tires" style="display: none;">
            <h3 style="margin-bottom: 15px;">Добавление комплекта резины</h3>
            <div class="mb-3">
                <label class="form-label" for="tires_brand">Марка</label>
                <input class="form-control" id="tires_brand" name="tires_brand" type="text" placeholder="Марка резины" require/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="marking">Маркировка</label>
                <input class="form-control" id="marking" name="marking" type="text" placeholder="Маркировка резины"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="tires_type">Тип резины</label>
                <select class="form-select" id="tires_type" name="tires_type" aria-label="Default select example" require>
                    <option value="" disabled selected>Выберите тип резины</option>
                    <?php //вывод списка типов резины
                    $tires = tires();
                    foreach ($tires as $tire) { 
                     echo '<option value="' . $tire['id'] . '">' . $tire['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="tires_date_buy">Дата покупки</label>
                <input class="form-control" id="tires_date_buy" name="tires_date_buy" type="date" min="1886-01-29" max="<?=date('Y-m-d')?>" placeholder="Дата покупки комплекта"/>
            </div>
            <button class="btn btn-primary" id="add_tires" name="add_tires" type="button" onclick="addTires(this)">Добавить</button>
        </div>

    </div>

</div>