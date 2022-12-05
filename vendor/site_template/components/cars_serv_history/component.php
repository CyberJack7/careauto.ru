<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//Стоимость услуги по id
function setAutoApplications($applications_list) {
    $applications_template = '';
    if (!empty($applications_list)) {
        foreach ($applications_list as $application) {
            $applications_template .= '<div class="plate" id="application_id_' . $application['id'] . '">
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
                            $applications_template .= '<div id="services">';
                            $count = 1;
                            foreach ($application['services'] as $service) {
                                $applications_template .= '<p>' . $count . '. ' . $service . '</p>';
                                $count++;
                            }
                            $applications_template .= '</div>';
                        } else {
                            $applications_template .= '<p class="value">Консультация и осмотр автомобиля</p>';
                        }
                        $applications_template .= '</div>
                    <div id="com_btn">
                        <div>
                            <p class="name"">Комментарий</p><p class="value">' . $application['text'] . '</p>
                        </div>';
                        if ($application['status'] == "Ожидает подтверждения" || $application['status'] == "Подтверждено") {
                            $applications_template .= '<div>
                                    <button class="btn btn-secondary" id="delete_application_id_' . $application['id'] . '" type="button" onclick="deleteApplication(this)">Отменить заявку</button>
                                </div>';
                        }
                        $applications_template .= '</div>
                        </div>
                    </div>';
        }
    } else {
        $applications_template .= '<div class="plate">
                <p>Для данного автомобиля не зафиксированно никаких записей в истории обслуживания</p>
            </div>';
    }
    return $applications_template;
  }


//получение списка услуг по id категории и передача в js
if (isset($_POST['show_applications'])) {
    $data = json_decode($_POST['show_applications'], true);
    $applications_list = getApplicationsListById($data['client_id'], $data['auto_id'], true);
    echo setAutoApplications($applications_list);
}