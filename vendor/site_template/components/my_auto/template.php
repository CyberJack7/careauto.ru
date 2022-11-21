<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/my_auto/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/my_auto/style.css">

<?php
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
?>

<div class="container">
<h1>Мои авто</h1>
    <div class="row">
        <div class="col-3">
            <div id="list-example" class="list-group">
                <?php
                $arResult = cars_list($_SESSION['user']['id']);
                $count = 0;
                if (empty($arResult)) {
                    echo '<p><div class="alert alert-primary" role="alert">Добавьте свой первый автомобиль!</div></p>';
                }
                foreach ($arResult as $car) { 
                    $count++;                   
                    echo '<a class="list-group-item list-group-item-action" href="#list-item-' . $count . '">'
                      . $car['brand'] . ' ' . $car['model'] . '</a>';
                }
                ?>
            </div>
        </div>
        <div class="col-8">
            <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                <h4 id="list-item-1">Item 1</h4>
                <p></p>
                <h4 id="list-item-2">Item 2</h4>
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
    </div>
</div>
