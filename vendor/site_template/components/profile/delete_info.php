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

if (isset($_POST['brand'])) {//редактировать список марок СЦ
    $sql = "DELETE FROM public.autoservice_brand WHERE autoservice_id = " . $_SESSION['user']['id'] . " AND brand_id = " . $_POST['brand'];
    $stmt = $pdo->exec($sql);
    exit;
}
if (isset($_POST['dataQuery'])) { //нажата кнопка del_photo или del_all_photos
    $ar_res_photos = json_decode($_POST['dataQuery'], true);
    $ar_bd_photos = getPhotosArray($_SESSION['user']['id']);
    if (empty($ar_res_photos)) { //удалить все фотографии СЦ
        $sql_photos_delete = "UPDATE public.autoservice SET photos = null WHERE autoservice_id = " . $_SESSION['user']['id'];
        $stmt = $pdo->exec($sql_photos_delete);
        foreach ($ar_bd_photos as $path_to_photo) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $path_to_photo);
        }    
        $_SESSION['message']['text'] = 'Фотографии успешно удалены!';
        $_SESSION['message']['type'] = 'success';
        exit;
    } else { //редактировать список фотографий СЦ
        foreach ($ar_bd_photos as $path_to_photo) {
            if (!in_array('http://localhost' . $path_to_photo, $ar_res_photos))
            unlink($_SERVER['DOCUMENT_ROOT'] . $path_to_photo);
        }
        $photos = [];
        foreach ($ar_res_photos as $photo) {
            array_push($photos, substr($photo, stripos($photo, '/uploads')));
        }
        $sql_photos = "{'" . implode("','", $photos) . "'}";
        $sql_quote_photos = $pdo->quote($sql_photos);
        $sql = "UPDATE public.autoservice SET photos = " . $sql_quote_photos . " WHERE autoservice_id = " . $_SESSION['user']['id'];     
        $stmt = $pdo->exec($sql);
        $_SESSION['message']['text'] = 'Данные изменены успешно!';
        $_SESSION['message']['type'] = 'success';
    }
} else { //нажата кнопка reset_requisites
    $requisites_id = getRequisitesId($_SESSION['user']['id']);
    
    $sql_requisites_delete = "DELETE FROM public.requisites WHERE requisites_id = " . $requisites_id;
    $stmt = $pdo->exec($sql_requisites_delete);
    
    $_SESSION['message']['text'] = 'Реквизиты успешно удалены!';
    $_SESSION['message']['type'] = 'success';
    exit;
}
