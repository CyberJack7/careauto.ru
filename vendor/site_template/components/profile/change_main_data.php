<?php
session_start();

//на страницу сохранения инфы может переадресовываться только автосервис
if ($_SESSION['user']['user_type'] != 'autoservice') {
    header('Location: /');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
require_once PATH_QUERIES;
$pdo = conn();


$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);


if ($name != $_SESSION['user']['name']) { //смена названия
    $sql_name = $pdo->quote($name);
    $sql = "UPDATE public.autoservice SET name_autoservice = " . $sql_name . " WHERE autoservice_id = " . $_SESSION['user']['id'];       
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['name'] = $name;
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}

$autoservice = get_all_userinfo($_SESSION['user']['id'], 'autoservice');

if ($description != $autoservice['text']) { //смена описания
    $sql_description = $pdo->quote($description);
    $sql = "UPDATE public.autoservice SET text = " . $sql_description . " WHERE autoservice_id = " . $_SESSION['user']['id'];     
    $stmt = $pdo->exec($sql);
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}


$photos = str_replace(['{', '}', "'"], '', $autoservice['photos']); //готовим строку к превращению в массив
$ar_photos = explode(',', $photos); //превращаем в массив

if ($autoservice['photos'] != null) { //если есть какие-то фото в бд
    $ar_name_photos = [];
    foreach ($ar_photos as $photo) {
        array_push($ar_name_photos, substr($photo, stripos($photo, '-')+1));
    }
    $ar_diff_photos = array_diff($_FILES['photos']['name'], $ar_name_photos);
    if (!empty($ar_diff_photos)) { //если загружаемые фото отличаются от тех, что в БД
        $directory = PATH_UPLOADS_REGULAR . $_SESSION['user']['id'] . '/photos/';
        $full_directory = $_SERVER['DOCUMENT_ROOT'] . $directory;
        foreach ($ar_diff_photos as $photo) { //для каждого отличного фото
            $photo_number = array_search($photo, $_FILES['photos']['name']);
            $photo_name = time() . '-' . $_FILES['photos']['name'][$photo_number];
            $download_path = $full_directory . $photo_name;
            if (!move_uploaded_file($_FILES['photos']['tmp_name'][$photo_number], $download_path)) {
                $_SESSION['message']['text'] = "Не удалось загрузить файл, попробуйте снова";
                $_SESSION['message']['type'] = 'warning';
                header('Location: /profile/');
                exit;
            }
            array_push($ar_photos, $directory . $_FILES['photos']['name'][$photo_number]);
        }
        $sql_photos = "{'" . implode("','", $ar_photos) . "'}";
        $sql_quote_photos = $pdo->quote($sql_photos);
        $sql = "UPDATE public.autoservice SET photos = " . $sql_quote_photos . " WHERE autoservice_id = " . $_SESSION['user']['id'];     
        $stmt = $pdo->exec($sql);
        $_SESSION['message']['text'] = 'Данные изменены успешно!';
        $_SESSION['message']['type'] = 'success';
    }
} else { //если фотографий в БД нет
    $sql_photos = '';
    $directory = PATH_UPLOADS_REGULAR . $_SESSION['user']['id'] . '/photos/';
    $full_directory = $_SERVER['DOCUMENT_ROOT'] . $directory;
    if (!is_dir($full_directory)) { //проверка директории на существование
        if (!mkdir($full_directory, 0777, true)) { //создание директории в случае отсутствия
            $_SESSION['message']['text'] = "Не удалось загрузить файл, попробуйте снова";
            $_SESSION['message']['type'] = 'warning';
            header('Location: /profile/');
            exit;
        } 
    }
    for ($photo_number = 0; $photo_number < count($_FILES['photos']['name']); $photo_number++) {
        $photo_name = time() . '-' . $_FILES['photos']['name'][$photo_number];
        $download_path = $full_directory . $photo_name;
        if (!move_uploaded_file($_FILES['photos']['tmp_name'][$photo_number], $download_path)) {
            $_SESSION['message']['text'] = "Не удалось загрузить файл, попробуйте снова";
            $_SESSION['message']['type'] = 'warning';
            header('Location: /profile/');
            exit;
        }
        $sql_photos .= ',' . "'" . $directory . $photo_name . "'";
    }
    $sql_photos = '{' . substr($sql_photos, 1) . '}';
    $sql_quote_photos = $pdo->quote($sql_photos);
    $sql = "UPDATE public.autoservice SET photos = " . $sql_quote_photos . " WHERE autoservice_id = " . $_SESSION['user']['id'];     
    $stmt = $pdo->exec($sql);
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}
header('Location: /profile/');
exit;