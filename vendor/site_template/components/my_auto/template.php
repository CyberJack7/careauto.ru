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
            $arResult = cars_list($_SESSION['user']['id']);
            $count = 0;
            if (empty($arResult)) { //Если авто нет
                echo '<p class="message_window"><div class="alert alert-info" role="alert">Добавьте свой первый автомобиль!</div></p>';
            }
            foreach ($arResult as $car) { 
                $count++;
                echo '<button class="list-group-item list-group-item-action" href="#list-item-' . $count . '">'
                  . $car['brand'] . ' ' . $car['model'] . '</button>';
            }
            ?>
        </div>
        <button class="btn btn-primary" id="change_main_data" name="change_main_data" type="submit">Добавить автомобиль</button>
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
        <div class="panel">
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
                    <!-- <?php //вывод списка моделей по id выбранной марки
                    /*$models = modelByPostId();
                    if($models != null) {
                        foreach ($models as $model) { 
                         echo '<option value="' . $model['id'] . '">' . $model['name'] . '</option>';
                        }
                    }*/
                    ?> -->
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="configuration">Комплектация</label>
                <input class="form-control" id="configuration" name="configuration" type="text" placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="auto_year">Год выпуска</label>
                <input class="form-control" id="auto_year" name="auto_year" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="date_buy">Дата покупки</label>
                <input class="form-control" id="date_buy" name="date_buy" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="body">Кузов</label>
                <input class="form-control" id="body" name="body" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="color">Цвет</label>
                <input class="form-control" id="color" name="color" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine_type">Тип двигателя</label>
                <input class="form-control" id="engine_type" name="engine_type" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine_volume">Объём двигателя</label>
                <input class="form-control" id="engine_volume" name="engine_volume" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="engine_power">Мощность двигателя</label>
                <input class="form-control" id="engine_power" name="engine_power" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="gearbox">Тип КПП</label>
                <input class="form-control" id="gearbox" name="gearbox" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="drive">Привод</label>
                <input class="form-control" id="drive" name="drive" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="PTS">ПТС</label>
                <input class="form-control" id="PTS" name="PTS" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="VIN">VIN</label>
                <input class="form-control" id="VIN" name="VIN" type="text" value="<?=$_SESSION['user']['name']?>" 
                    placeholder="Название Вашего сервисного центра" required/>
            </div>
        </div>
    </div>
</div>