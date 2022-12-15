<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/cars_serv_history/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/cars_serv_history/style.css">

<?php
    //на странице истории обслуживаний машин автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
    getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>

<h1 class="container">История обслуживания</h1>
<div class="container central" id="<?=$_SESSION['user']['id']?>">
    <div class="navigation">
        <div class="list-group" id="list-example">
            <?php
            $cars = cars_list($_SESSION['user']['id']);
            $count = 0;
            if (empty($cars)) { //Если авто нет
                echo '<p class="message_window"><div class="alert alert-info" role="alert">Список автомобилей пуст</div></p>';
            }
            foreach ($cars as $car) { 
                if ($count == 0) {
                    echo '<button class="list-group-item list-group-item-action item_active" id="' . $car['id'] . '" onclick="showAutoServHistory(this)">'
                    . $car['brand'] . ' ' . $car['model'] . '</button>';
                } else {
                    echo '<button class="list-group-item list-group-item-action" id="' . $car['id'] . '" onclick="showAutoServHistory(this)">'
                    . $car['brand'] . ' ' . $car['model'] . '</button>';
                }
                $count++;
            }
            ?>
        </div>
    </div>

    <div class="panel" id="show_applications">
        <?php 
            $history_list = getAutoHistoryById($_SESSION['user']['id'], $cars[0]['id']);
            echo setAutoHistory($history_list);
        ?>
    </div>

</div>