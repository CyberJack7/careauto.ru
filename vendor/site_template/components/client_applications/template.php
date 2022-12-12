<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/client_applications/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/client_applications/style.css">

<?php 
    //на странице автомобилей автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
    getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>

<h1 class="container" id="<?=$_SESSION['user']['id']?>">Список заявок</h1>
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
                        <p class="name">Стоимость</p><p class="value" id="price_' . $application['id'] . '">' . $application['price'] . ' р</p>
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
                            <p class="name"">Комментарий</p><p class="value">' . $application['text'] . '</p>';
                        if ($application['status'] == "Ожидает подтверждения" || $application['status'] == "Подтверждено") {
                            echo '</div><div>
                                    <button class="btn btn-secondary" id="delete_application_id_' . $application['id'] . '" type="button" onclick="deleteApplication(this)">Отменить заявку</button>
                                </div>';
                        } else if ($application['status'] == "Выполнено") {
                            echo '<p class="name"">Статус оплаты</p>';
                            if ($application['date_payment'] == '-') {
                                echo '<p class="value">Не оплачено</p></div>';
                                if (getRequisitesInfo($application['autoservice_id']) != NULL) {
                                    echo '<div>
                                            <button class="btn btn-primary" id="pay_application_id_' . $application['id'] . '" type="button" onclick="payApplication(this)">Оплатить</button>
                                        </div>';
                                } else {
                                    echo '<p><div class="alert alert-info" role="alert">Для данного сервисного центра не доступна онлайн-оплата</div></p>';
                                }
                            } else {
                                echo '<p class="value">Оплачено</p>
                                    <p class="name"">Дата и время оплаты</p>
                                    <p class="value">' . $application['date_payment'] . '</p></div>';
                            }
                        } else {
                            echo '</div>';
                        }
                    echo '</div>
                        </div>
                        </div>';
                        
        }
    }
    ?>
</div>