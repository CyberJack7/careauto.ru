<?php
session_start();

//на страницу сохранения инфы может переадресовываться только авторизованный пользователь
if ($_SESSION['user']['user_type'] != 'autoservice') {
    header('Location: /');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;
$pdo = conn();

$requisites_id = requisites_id($_SESSION['user']['id']);

$sql_requisites_delete = "DELETE FROM public.requisites WHERE requisites_id = " . $requisites_id;
$stmt = $pdo->exec($sql_requisites_delete);

$_SESSION['message']['text'] = 'Реквизиты успешно удалены!';
$_SESSION['message']['type'] = 'success';
exit;