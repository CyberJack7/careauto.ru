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


if ($_POST['del_photo'] === true) {

} elseif ($_POST['del_all_photos'] === true) {
    $sql_photos_delete = "UPDATE public.autoservice SET photos = null WHERE autoservice_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql_requisites_delete);

    $_SESSION['message']['text'] = 'Фотографии успешно удалены!';
    $_SESSION['message']['type'] = 'success';
    // exit;
} else {
    $requisites_id = getRequisitesId($_SESSION['user']['id']);
    
    $sql_requisites_delete = "DELETE FROM public.requisites WHERE requisites_id = " . $requisites_id;
    $stmt = $pdo->exec($sql_requisites_delete);
    
    $_SESSION['message']['text'] = 'Реквизиты успешно удалены!';
    $_SESSION['message']['type'] = 'success';
    exit;
}