<?php
if (!isset($_SESSION['user']['id'])) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/queries.php';
require_once PATH_CONNECT;
function change_status($appl_id, $status, $date = '0', $time = '0', $ArServices, $price, $text_autoservice = null, $date_payment = null)
{
    $pdo = conn();
    switch ($status) {
        case "Ожидает подтверждения":
            $new_date = $date . ' ' . $time;
            $sql = "UPDATE Public.application SET date=" . $pdo->quote($new_date) . "WHERE application_id=" . $appl_id;
            $result = $pdo->exec($sql);
            $new_status = "Подтверждено";
            break;
        case "Подтверждено":
            $new_status = "В работе";
            break;
        case "В работе":
            $new_status = "Выполнено";
            break;
        case "Отказ":
            $new_status = "Отказ";
            break;
        case "Выполнено":
            $new_status = "Завершено";
            if ($date_payment == 'null') {
                date_default_timezone_set('Europe/Moscow');
                $date_pay = $pdo->quote(date('Y-m-d h:i:s', time()));
            } else {
                $date_pay = $pdo->quote($date_payment);
            }
            $sql = "UPDATE Public.application SET date_payment=" . $date_pay . "WHERE application_id=" . $appl_id;
            $result = $pdo->exec($sql);
            break;
    }

    $sql = "UPDATE Public.application SET status=" . $pdo->quote($new_status) . ",
    price=" . $price . ",
    autoserv_serv_id=" . $pdo->quote($ArServices) . "
     WHERE application_id=" . $appl_id;
    $result = $pdo->exec($sql);
    if ($new_status == "Завершено") {
        $sql_ins = "INSERT INTO public.application_history(client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment)
                        SELECT client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment FROM public.application
                        WHERE application_id=" . $appl_id;

        $sql_ins_client_history = "INSERT INTO public.client_history(client_id,auto_id,autoserv_serv_id,price,date,date_payment,text)
                        SELECT client_id,auto_id,autoserv_serv_id,price,date,date_payment,text FROM public.application
                        WHERE application_id=" . $appl_id;
        $result_ins = $pdo->exec($sql_ins);
        $appl_history_id = $pdo->lastInsertId();
        $result_ins_client = $pdo->exec($sql_ins_client_history);
        $appl_client_history_id = $pdo->lastInsertId();
        $sql_get_name_autoservice = "SELECT name_autoservice FROM public.autoservice WHERE
        autoservice_id=" . $_SESSION['user']['id'];
        $autoservice_name = $pdo->query($sql_get_name_autoservice)->fetch();
        $text_autoservice = $pdo->quote($text_autoservice);
        $autoservice_name = $pdo->quote($autoservice_name['name_autoservice']);
        $sql_upd_autoservice = "UPDATE public.application_history SET text=$text_autoservice
        WHERE application_id=" . $appl_history_id;
        $sql_upd_client = "UPDATE public.client_history SET text=$text_autoservice,
        name_autoservice=" . $autoservice_name . " WHERE history_id=" . $appl_client_history_id;
        $result1 = $pdo->exec($sql_upd_autoservice);
        $result2 = $pdo->exec($sql_upd_client);
        $sql_del = "DELETE 
                            FROM Public.application
                            WHERE application_id=$appl_id";
        $result = $pdo->exec($sql_del);
    } elseif ($new_status == "Отказ") {
        $sql_ins = "INSERT INTO public.application_history(client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment)
        SELECT client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment FROM public.application
        WHERE application_id=" . $appl_id;
        $result_ins = $pdo->exec($sql_ins);
        $appl_history_id = $pdo->lastInsertId();
        $sql_upd_autoservice = "UPDATE public.application_history SET text=$text_autoservice
        WHERE application_id=" . $appl_history_id;
        $result1 = $pdo->exec($sql_upd_autoservice);
        $sql_del = "DELETE 
        FROM Public.application
        WHERE application_id=$appl_id";
        $result = $pdo->exec($sql_del);
    }
}

function getCarHistory($appl_id)
{
    $pdo = conn();
    $sql = "SELECT client_id,auto_id FROM public.application WHERE
    application_id=" . $appl_id;
    $count_displayed = 0;

    $result = $pdo->query($sql)->fetch();
    if ($result) {
        $history_list = getAutoHistoryById($result['client_id'], $result['auto_id']);
        if (!empty($history_list)) {
            echo '<div class="modal" tabindex="-1">
                 <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">' . $history_list[0]['auto'] . '</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div id="history_area">';
            foreach ($history_list as $history) {
                if ($history['confidentiality'] != 1) {
                    $count_displayed++;
                    echo '<div class="plate" id="history_id_' . $history['id'] . '">';
                    if ($history['autoservice'] == '-') {
                        echo '<h3>Запись ' . $history['id'] . '</h3>';
                    } else {
                        echo '<h3>' . $history['autoservice'] . '</h3>';
                    }
                    echo '<div class="flex">
                    <div>
                        <p class="name">Дата</p><p class="value">' . $history['date'] . '</p>
                        <p class="name">Время</p><p class="value">' . $history['time'] . '</p>
                        </div>
                        <div>
                        <p class="name">Стоимость</p><p class="value">' . $history['price'] . ' р</p>
                        <p class="name">Список услуг</p>';
                    if (!empty($history['services'])) {
                        echo '<div id="services">';
                        $count = 1;
                        foreach ($history['services'] as $service) {
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
                <p class="name"">Комментарий</p><p class="value">' . $history['text'] . '</p>
                </div>';

                    echo '</div>
                </div>
                </div>';
                }
            }
            if ($count_displayed == 0) {
                echo '<h3>Иcтория обслуживания пуста</h3>
               <div class="plate">        
                   <p>Для данного автомобиля не зафиксированно никаких записей в истории обслуживания. Либо они скрыты пользователем!</p>
               </div>';
            }
            echo ' </div>        
            <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть историю</button>
        </div>
        </div>
        </div>
        </div>';
        } else {
            echo '<div class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
       <div class="modal-content">
       <div class="modal-header">
       <h5 class="modal-title">' . "Ууууупс..." . '</h5>
       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
       <div id="history_area">';
            echo '<p>У данного авто нет истории!</p>';

            echo ' </div>        
       <div class="modal-footer">
   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть историю</button>
   </div>
   </div>
   </div>
   </div>';
        }
    }
}

function show_complaint($appl_id)
{ // $user_id - тот, на кого жалуемся // text - причина из-за которой жалуемся // $appl_id - ид заявки
    $pdo = conn();
    $sql = "SELECT name_client,client_id FROM public.application JOIN public.client USING(client_id) WHERE application_id=" . $appl_id;
    $client = $pdo->query($sql)->fetch();
    $sql_check_complaint = "SELECT complainant_id FROM public.complaint WHERE complainant_id=" . $_SESSION['user']['id']
        . " AND inspected_user_id=" . $client['client_id'];
    $result = $pdo->query($sql_check_complaint)->fetch();
    if ($result) {
        $text_modal = '<p>Вы уже отправляли жалобу на ' . $client['name_client'] . ' <br>Администратор обязательно проверит её</p>';
        $button_accept = '<button type="button" data-bs-dismiss="modal" class="btn btn-primary">Понятно</button>';
    } else {
        $text_modal = '<p>Опишите причину жалобы в поле ниже. Спасибо, что делаете мир лучше!</p>
        
        <div class="form-floating" name="complaint_text">
              <textarea class="form-control" placeholder="Причина жалобы" id="complaint_' . $appl_id . '" maxlength="200" style="height: 100px"></textarea>
              <label for="complaint_' . $appl_id . '">Причина жалобы</label>
        </div>';
        $button_accept = '<button onclick="sendcomplaint(this)" type="button" data-bs-dismiss="modal" value="' . $appl_id . '" class="btn btn-primary">Отправить жалобу</button>';
    }


    echo '<div class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Оформление жалобы на ' . $client['name_client'] . '</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        ' . $text_modal . '       

        </div>
        <div class="modal-footer">
        ' . $button_accept . '
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>';
}




function getStartService($appl_numb, $ArCategory, $ApplServices)  // $ApplServices - услуги указанные в заявке
{                                                                // $appl_numb - порядковый номер заявки
    // $ArCategory - список категорий СЦ, с указанием отмечены ли они изначально
    $pdo = conn();
    $AllCategoryAutoServ = getAutoserviceCategoryList($_SESSION['user']['id']); // Получаем список всех категорий СЦ с названием категорий
    $AllServicesAutoServ = getAutoserviceServiceList($_SESSION['user']['id']);
    foreach ($ArCategory as $key => $value) { // $key - category_id $value - checked or not checked
        if ($value == "true") {
            $ar_service = getAutoServiceServById($_SESSION['user']['id'], $key); // список услуг сервиса по категории
            if ($ar_service != null) {
                echo '<div id="' . $key . '" class="services_by_category_' . $appl_numb . '">';
                echo '<p id="' . $key . '" >
                <b>' . $AllCategoryAutoServ[$key] . '</b>
                </p>';
                foreach ($ar_service as $id_serv => $name_serv) {
                    if (is_array($ApplServices) && in_array($id_serv, $ApplServices))
                        $checked = " checked ";
                    else
                        $checked = " ";
                    echo '
                    <label for="' . $appl_numb . 'serv_' . $id_serv . '">
                    <input onclick="ServiceCounter(this);getStartAmount(this)"' . $checked . 'id="' . $appl_numb . 'serv_' . $id_serv . '" type="checkbox" value="' . $appl_numb . '">
                    ' . $name_serv . '
                    </label>';
                }
                echo '</div>';
            } else {
            }
        } else {
        }
    }

    $flag = true;

    if ($ApplServices != "null") {
        foreach ($ApplServices as $id_serv) {
            if (!array_key_exists($id_serv, $AllServicesAutoServ)) {
                if ($flag) {
                    echo '<div id="Not_In_Autoserv_' . $appl_numb . '" class="services_by_category_' . $appl_numb . '">';
                    echo '<p id="Not_In_Autoserv" >
                <b style="color:red" >' . "Услуги больше не предоставляемые СЦ" . '</b>
                </p>';
                    $flag = false;
                }
                echo '<label for="' . $appl_numb . 'serv_' . $id_serv . '">
                    <input onchange="ServiceCounter(this)" checked id="' . $appl_numb . 'serv_' . $id_serv . '" type="checkbox" value="' . $appl_numb . '">
                    ' . getServiceNameById($id_serv) . '
                    </label>';
            }
        }
        if (!$flag)
            echo '</div>';
    }
}

if (!empty($_POST['appl_numb']) and !empty($_POST['ArCategory']) and !empty($_POST['ApplServices'])) {
    getStartService($_POST['appl_numb'], $_POST['ArCategory'], $_POST['ApplServices']);
}


if (!empty($_POST['status']) and !empty($_POST['appl_id']) and !empty($_POST['ArService']) and !empty($_POST['price'])) {
    if (!empty($_POST['text_autoservice']) and !empty($_POST['date_payment']))
        change_status($_POST['appl_id'], $_POST['status'], $_POST['date'], $_POST['time'], $_POST['ArService'], $_POST['price'], $_POST['text_autoservice'], $_POST['date_payment']);
    else {
        change_status($_POST['appl_id'], $_POST['status'], $_POST['date'], $_POST['time'], $_POST['ArService'], $_POST['price']);
    }
} else {
}

if (!empty($_POST['appl_numb']) and !empty($_POST['ArForPrices']) and !empty($_POST['Operation'])) {
    if ($_POST['Operation'] == "start")
        echo getTotalAmount($_POST['ArForPrices'], $_SESSION['user']['id']);
    elseif ($_POST['Operation'] == "+") {
        echo $_POST['Old_value'] + getTotalAmount($_POST['ArForPrices'], $_SESSION['user']['id']);
    } elseif ($_POST['Operation'] == "-") {
        echo $_POST['Old_value'] - getTotalAmount($_POST['ArForPrices'], $_SESSION['user']['id']);
    }
}

if (!empty($_POST['appl_numb']) and !empty($_POST['get_car'])) {
    getCarHistory($_POST['appl_numb']);
}
if (!empty($_POST['appl_numb']) and !empty($_POST['show_complaint'])) {
    show_complaint($_POST['appl_numb']);
}

if (!empty($_POST['appl_numb']) and !empty($_POST['text_complaint'])) {
    $pdo = conn();
    $appl_id = $pdo->quote($_POST['appl_numb']);
    $sql = "SELECT client_id FROM public.application WHERE application_id=" . $appl_id;
    $client = $pdo->query($sql)->fetch();
    send_complaint($client['client_id'], $_POST['text_complaint'], $_SESSION['user']['id']); // в queries
}