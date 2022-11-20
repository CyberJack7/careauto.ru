<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
function change_status($appl_id, $status)
{
    $pdo = conn();
    switch ($status) {
        case "Ожидает подтверждения":
            $new_status = "Подтверждено";
            break;
        case "Подтверждено":
            $new_status = "В работе";
            break;
        case "В работе":
            $new_status = "Выполнено";
            break;
    }
    $sql = "UPDATE Public.application SET status=" . $pdo->quote($new_status) . "WHERE application_id=" . $appl_id;
    $result = $pdo->exec($sql);
}
if (!empty($_POST['status']) and !empty($_POST['appl_id'])) {
    change_status($_POST['appl_id'], $_POST['status']);
} else {
}