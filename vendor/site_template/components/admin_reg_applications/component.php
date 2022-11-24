<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
function add_autoservice($autoservice_temp_id, $status, $email, $document)
{
    $pdo = conn();
    if ($status == 'Принять') {
        $sql_ins = "INSERT INTO Public.autoservice(name_autoservice,email_autoservice,password_autoservice,phone_autoservice,document,city_id)
        SELECT name_autoservice,email_autoservice,password_autoservice,phone_autoservice,document,city_id
        FROM Public.autoservice_in_check WHERE autoservice_temp_id=" . $autoservice_temp_id;
        $res = $pdo->exec($sql_ins);
        $str = str_replace('/uploads/temp/', '', $document);
        $autoservice_id = $pdo->lastInsertId();
        mkdir($_SERVER['DOCUMENT_ROOT'] . PATH_UPLOADS_REGULAR . $autoservice_id . '/document/', 0777, true);
        $new_directory = PATH_UPLOADS_REGULAR . $autoservice_id . '/document/' . $str;
        rename($_SERVER['DOCUMENT_ROOT'] . $document, $_SERVER['DOCUMENT_ROOT'] . $new_directory);
        $sql = "UPDATE Public.autoservice SET document=" . $pdo->quote($new_directory) . "WHERE autoservice_id=" . $autoservice_id;
        $result = $pdo->exec($sql);
        send_email($email, 'reg_complete');
    } else {
        send_email($email, 'reg_fail');
    }
    $sql_del = "DELETE FROM Public.autoservice_in_check
    WHERE autoservice_temp_id=" . $autoservice_temp_id;
    $res = $pdo->exec($sql_del);
}
if (!empty($_POST['autoserv_temp_id']) and !empty($_POST['status']) and !empty($_POST['email']) and !empty($_POST['document'])) {
    add_autoservice($_POST['autoserv_temp_id'], $_POST['status'], $_POST['email'], $_POST['document']);
} else {
}