<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/client_applications/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/client_applications/style.css">

<?php 
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
?>

<h1 class="container">Список заявок</h1>
<div class="container column">
    <?php //вывод всех текущих заявок клиента
    $applications = getApplicationsListById($_SESSION['user']['id']);
    if (!empty($applications)) {
        foreach ($applications as $application) {
        echo '<div class="plate" id="application_id_' . $application['id'] . '">
                <h3>' . $application['autoservice'] . ' - ' . $application['auto'] . '</h3>
                <div class="flex">
                    <div>
                        <p class="name">Статус</p><p class="value">' . $application['status'] . '</p>
                        <p class="name">Дата</p><p class="value">' . $application['date'] . '</p>
                        <p class="name">Время</p><p class="value">' . $application['time'] . '</p>
                    </div>
                    <div>
                        <p class="name">Стоимость</p><p class="value">' . $application['price'] . ' р</p>
                        <p class="name">Список услуг</p>';
                        if (!empty($application['services'])) {
                            echo '<div id="services">';
                            $count = 1;
                            foreach ($application['services'] as $service) {
                                echo '<p>' . $count . '. ' . $service . '</p>';
                                $count++;
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="value">Консультация и осмотр автомобиля</p>';
                        }
                    echo '</div>
                    <div id="com_btn">
                        <div>
                            <p class="name"">Комментарий</p><p class="value">' . $application['text'] . '</p>
                        </div>';
                        if ($application['status'] == "Ожидает подтверждения" || $application['status'] == "Подтверждено") {
                            echo '<div>
                                    <button class="btn btn-secondary" id="delete_application_id_' . $application['id'] . '" type="button" onclick="deleteApplication(this)">Отменить заявку</button>
                                </div>';
                        }
                    echo '</div>
                        </div>
                    </div>';
        }
    }
    ?>
</div>