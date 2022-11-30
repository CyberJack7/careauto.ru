<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
function change_status($appl_id, $status, $date = '0', $time = '0')
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

    $sql = "UPDATE Public.application SET status=" . $pdo->quote($new_status) . "WHERE application_id=" . $appl_id;
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
if (!empty($_POST['status']) and !empty($_POST['appl_id'])) {
    change_status($_POST['appl_id'], $_POST['status'], $_POST['date'], $_POST['time']);
} else {
}