<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//отобразить историю обслуживания авто
function setAutoHistory($history_list) {
    $history_template = '';
    if (!empty($history_list)) {
        $history_template .= '<h3>' . $history_list[0]['auto'] . '</h3>
            <div id="history_area">';
        foreach ($history_list as $history) {
            $history_template .= '<div class="plate" id="history_id_' . $history['id'] . '">';
                if ($history['autoservice'] == '-') {
                    $history_template .= '<h3>Запись ' . $history['id'] . '</h3>';
                } else {
                    $history_template .= '<h3>' . $history['autoservice'] . '</h3>';
                }
                $history_template .= '<div class="flex">
                    <div>
                        <p class="name">Дата</p><p class="value">' . $history['date'] . '</p>
                        <p class="name">Время</p><p class="value">' . $history['time'] . '</p>
                    </div>
                    <div>
                        <p class="name">Стоимость</p><p class="value">' . $history['price'] . ' р</p>
                        <p class="name">Список услуг</p>';
                        if (!empty($history['services'])) {
                            $history_template .= '<div id="services">';
                            $count = 1;
                            foreach ($history['services'] as $service) {
                                $history_template .= '<p>' . $count . '. ' . $service . '</p>';
                                $count++;
                            }
                            $history_template .= '</div>';
                        } else {
                            $history_template .= '<p class="value">Консультация и осмотр автомобиля</p>';
                        }
                        $history_template .= '</div>
                    <div id="com_btn">
                        <div>
                            <p class="name"">Комментарий</p><p class="value">' . $history['text'] . '</p>
                        </div>';
                        $checked = '';
                        if ($history['confidentiality'] == 1) {
                            $checked = ' checked ';
                        }
                        $history_template .= '<div>                        
                                <label for="confidentiality">
                                <input type="checkbox"' . $checked . 'id="confidentiality_' . $history['id'] . '" onclick="setConfidentiality(this)"/>Скрыть от автосервисов</label>
                                <button class="btn btn-outline-danger" id="delete_history_id_' . $history['id'] . '" type="button" onclick="deleteHistoryRecord(this)">Удалить</button>
                            </div>';
                        $history_template .= '</div>
                        </div>
                    </div>';
        }
    } else {
        $history_template .= '<h3>Иcтория обслуживания пуста</h3>
            <div class="plate">        
                <p>Для данного автомобиля не зафиксированно никаких записей в истории обслуживания</p>
            </div>';
    }
    $history_template .= '<button class="btn btn-primary" id="add_history_record" type="button" onclick="addHistoryRecord(this)">Добавить новую запись</button></div>';
    return $history_template;
  }


//отобразить историю обслуживания авто
function showAddHistoryRecord() {
    $add_history_template = '<div class="panel" id="add_history">
            <h3>Добавление записи в историю обслуживания</h3>
            <div class="mb-3">
                <label class="form-label" for="name_autoservice">Название сервисного центра</label>
                <input class="form-control" id="name_autoservice" name="name_autoservice" type="text" onchange="validInput(this)" placeholder="Название сервисного центра"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="date">Дата</label>
                <input class="form-control" id="date" name="date" type="date" min="1886-01-29" max="' . date('Y-m-d') . '" onchange="validInput(this)" placeholder="Дата обслуживания"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="price">Стоимость обслуживания</label>
                <input class="form-control" id="price" name="price" type="number" min="0" onchange="validInput(this)" placeholder="Стоимость обслуживания"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="text">Описание</label>
                <textarea class="form-control" id="text" name="text" maxlength="200" type="textarea" onchange="validInput(this)" placeholder="Описание"></textarea>
            </div>
            <div class="multiselect mb-3">
                <label class="form-label" for="services">Перечень выполненных работ</label>
                <div class="checkboxes" id="services">';
                    $services = getAllServicesList();
                    foreach ($services as $service) {
                        $add_history_template .= '<label for="service_id_' . $service['id'] . '">
                            <input type="checkbox" id="service_id_' . $service['id'] . '"/>' . $service['name'] . '</label>';
                    }
                $add_history_template .= '</div>
            </div>
            <div class="btn_div">
                <button class="btn btn-primary" id="create_history_record" type="button" onclick="createHistoryRecord(this)">Добавить</button>
                <button class="btn btn-secondary" id="cancel_history_record" type="button" onclick="cancelAddHistoryRecord(this)">Отменить</button>
            </div>
        </div>';
    
    return $add_history_template;
}



//отображение истории обслуживания автомобиля
if (isset($_POST['show_applications'])) {
    $data = json_decode($_POST['show_applications'], true);
    $history_list = getAutoHistoryById($data['client_id'], $data['auto_id']);
    echo setAutoHistory($history_list);
}


//установка значения конфиденциальности для записи
if (isset($_POST['confid_history'])) {
    $data = json_decode($_POST['confid_history'], true);
    $sql = "UPDATE public.client_history SET confidentiality = " . $data['confidentiality'] . " WHERE history_id = " . $data['history_id'];
    $stmt = $pdo->exec($sql);
}


//удаление записи об обслуживании
if (isset($_POST['delete_history'])) {
    $history_id = json_decode($_POST['delete_history'], true);
    $sql = "DELETE FROM public.client_history WHERE history_id = " . $history_id;
    $stmt = $pdo->exec($sql);
}

//окно добавления записи об обслуживании
if (isset($_POST['add_history'])) {
    echo showAddHistoryRecord();
}


//добавление записи об обслуживании
if (isset($_POST['create_history_record'])) {
    $data = json_decode($_POST['create_history_record'], true);
    foreach ($data as &$value) {
        if ($value == '') {
            $value = NULL;
        }
    }
    if (empty($data['autoserv_serv_id'])) {
        $data['autoserv_serv_id'] = NULL;
    }
    $sql = "INSERT INTO public.client_history(client_id, auto_id, autoserv_serv_id, price, date, date_payment, text, name_autoservice) 
        VALUES (:client_id, :auto_id, :autoserv_serv_id, :price, :date, :date_payment, :text, :name_autoservice)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'client_id' => $data['client_id'],
        'auto_id' => $data['auto_id'],
        'autoserv_serv_id' => $data['autoserv_serv_id'],
        'price' => $data['price'],
        'date' => $data['date'],
        'date_payment' => $data['date'],
        'text' => $data['text'],
        'name_autoservice' => $data['name_autoservice']
    ]);
    
}