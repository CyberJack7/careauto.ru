<?php
if (!isset($_SESSION['user']['id'])) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/queries.php';
require_once PATH_CONNECT;
function change_status($appl_id, $status, $date = '0', $time = '0', $ArServices, $price)
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
            $date_pay = date('Y-m-d h:i:s', time());
            $sql = "UPDATE Public.application SET date_payment=" . $pdo->quote($date_pay) . "WHERE application_id=" . $appl_id;
            $result = $pdo->exec($sql);
            break;
    }

    $sql = "UPDATE Public.application SET status=" . $pdo->quote($new_status) . ",
    price=" . $price . ",
    autoserv_serv_id=" . $pdo->quote($ArServices) . "
     WHERE application_id=" . $appl_id;
    $result = $pdo->exec($sql);
    if (($new_status == "Отказ") || ($new_status == "Завершено")) {
        $sql_ins = "INSERT INTO Public.application_history(client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment)
                        SELECT client_id,auto_id,autoservice_id,date,autoserv_serv_id,price,text,status,date_payment FROM Public.application
                        WHERE application_id=$appl_id";
        $result = $pdo->exec($sql_ins);
        $sql_del = "DELETE 
                        FROM Public.application
                        WHERE application_id=$appl_id";
        $result = $pdo->exec($sql_del);
    }
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
    change_status($_POST['appl_id'], $_POST['status'], $_POST['date'], $_POST['time'], $_POST['ArService'], $_POST['price']);
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