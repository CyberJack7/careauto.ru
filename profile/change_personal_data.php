<?php
session_start();

//на страницу сохранения инфы может переадресовываться только авторизованный пользователь
if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
$pdo = conn();

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars(str_replace(['(', ')', '-', ' '], '', $_POST['phone']));
$city_id = htmlspecialchars($_POST['city_id']);

// if ($email != $_SESSION['user']['name'])
if ($name != $_SESSION['user']['name']) { //смена ФИО
    $sql_name = $pdo->quote($name);
    $sql = "UPDATE Public.client SET name_client = " . $sql_name . " WHERE client_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['name'] = $name;
}
if ($phone != $_SESSION['user']['phone']) { //смена номера телефона
    $sql_phone = $pdo->quote($phone);
    $sql = "UPDATE Public.client SET phone_client = " . $sql_phone . " WHERE client_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['phone'] = $phone;
}
if ($city_id != $_SESSION['user']['city_id']) { //смена города
    $sql_phone = $pdo->quote($city_id);
    $sql = "UPDATE Public.client SET city_id = " . $city_id . " WHERE client_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['city_id'] = $city_id;
}
if ($email != $_SESSION['user']['email']) { //смена почты через подтверждение
    $_SESSION['user'] += [
        "new_email" => $email,
        "code" => send_email($_POST['email']), //одновременная отправка и генерация кода
        "attempt" => 3
    ];
    header('Location: /confirmation_code/');
    exit;
}
header('Location: /profile/');
exit;