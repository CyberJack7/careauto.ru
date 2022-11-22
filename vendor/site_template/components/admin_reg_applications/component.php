<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
function add_autoservice($autoservice_temp_id, $status, $email)
{
    $pdo = conn();
    if ($status == 'Принять') {
        $sql_ins = "INSERT INTO Public.autoservice(name_autoservice,email_autoservice,password_autoservice,phone_autoservice,document,city_id)
        SELECT name_autoservice,email_autoservice,password_autoservice,phone_autoservice,document,city_id
        FROM Public.autoservice_in_check WHERE autoservice_temp_id=" . $autoservice_temp_id;
        $res = $pdo->exec($sql_ins);
        send_email($email, 'reg_complete');
    } else {
        send_email($email, 'reg_fail');
    }
    $sql_del = "DELETE FROM Public.autoservice_in_check
    WHERE autoservice_temp_id=" . $autoservice_temp_id;
    $res = $pdo->exec($sql_del);
}
if (!empty($_POST['autoserv_temp_id']) and !empty($_POST['status']) and !empty($_POST['email'])) {
    add_autoservice($_POST['autoserv_temp_id'], $_POST['status'], $_POST['email']);
} else {
}