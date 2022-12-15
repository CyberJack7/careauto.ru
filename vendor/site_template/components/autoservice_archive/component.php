<?php
if (!isset($_SESSION['user']['id'])) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/queries.php';
require_once PATH_CONNECT;


// Отобразить заявки СЦ
function setAutoserviceHistory($history_list, $status)
{
    if (!empty($history_list)) {
        foreach ($history_list as $history) {
            if ($status == "Отказ") {
                $date_button = $history['date_start'];
                $time_button = $history['time_start'];
            } else {
                $date_button = $history['date_payment'];
                $time_button = $history['time_payment'];
            }
            echo '<div id="appl_"' . $history['id'] . '>
                <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-heading' . $history['id'] . '">
                <button value="' . $history['id'] . '" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $history['id'] . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $history['id'] . '">' .
                $date_button . ' ' . $time_button . ' ' . $history['name_client'] . ' ' . $history['auto'] .
                '</button>
                            </h2>
                            <div id="panelsStayOpen-collapse' . $history['id'] . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $history['id'] . '">
                            <div class="accordion-body">' .
                'ФИО Клиента: ' . $history['name_client'] . '</br> ' .
                'Телефон Клиента: ' . $history['phone_client'] . '</br> ' .
                'Автомобиль: ' . $history['auto'] . '</br> ' .
                'Список услуг: </br>';
            $serv_count = 0;
            foreach ($history['services'] as $service) {
                $serv_count++;
                echo $serv_count . '. ' . $service . '</br>';
            }
            echo  'Стоимость услуг: ' . $history['price'] . ' Рублей. </br>
            Комментарий: ' . $history['text'] . '</br>

            Дата подачи заявки: ' . $history['date_start'] . '</br>
            Время подачи заявки: ' . $history['time_start'] . '</br>';

            if ($status != "Отказ") {

                echo '
                Дата оплаты: ' . $history['date_payment'] . '</br>
                
                Время оплаты: ' . $history['time_payment'] . '</br>';
            }






            echo '</div></div></div></div>';


            //echo '</div>';
        }
    }
    // return $history_template;
}