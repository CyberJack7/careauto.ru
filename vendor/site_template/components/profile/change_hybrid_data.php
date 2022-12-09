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

function email_check($email, $pdo)
{
    $sql_array_check = [
        "check_admin_sql" => "SELECT * 
    FROM Public.admin 
    WHERE email_admin = '$email'",

        "check_autoservice_in_check_sql" => "SELECT * 
    FROM Public.autoservice_in_check 
    WHERE email_autoservice = '$email'",

        "check_autoservice_sql" => "SELECT * 
    FROM Public.autoservice 
    WHERE email_autoservice = '$email'",

        "check_client_sql" => "SELECT * 
    FROM Public.client 
    WHERE email_client = '$email'"
    ];

    foreach ($sql_array_check as $sql) {
        $check_user = $pdo->query($sql);
        if ($check_user->fetchColumn() > 0) {
            $_SESSION['message']['text'] = "Пользователь с таким email уже зарегистрирован!";
            $_SESSION['message']['type'] = 'warning';
            header('Location: /profile/');
            exit;
        } else {
            continue;
        }
    }
    return;
}

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars(str_replace(['(', ')', '-', ' '], '', $_POST['phone']));
$city_id = htmlspecialchars($_POST['city_id']);
if ($_SESSION['user']['user_type'] == 'client') { //смена ФИО
    if ($name != $_SESSION['user']['name']) { 
        $sql_name = $pdo->quote($name);
        $sql = "UPDATE Public.client SET name_client = " . $sql_name . " WHERE client_id = " . $_SESSION['user']['id'];       
        $stmt = $pdo->exec($sql);
        $_SESSION['user']['name'] = $name;
        $_SESSION['message']['text'] = 'Данные изменены успешно!';
        $_SESSION['message']['type'] = 'success';
    }        
}
if ($phone != $_SESSION['user']['phone']) { //смена номера телефона
    $sql_phone = $pdo->quote($phone);
    if ($_SESSION['user']['user_type'] == 'client') {
        $sql = "UPDATE Public.client SET phone_client = " . $sql_phone . " WHERE client_id = " . $_SESSION['user']['id'];
    } else {
        $sql = "UPDATE Public.autoservice SET phone_autoservice = " . $sql_phone . " WHERE autoservice_id = " . $_SESSION['user']['id'];
    }      
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}
if ($city_id != $_SESSION['user']['city_id']) { //смена города
    if ($_SESSION['user']['user_type'] == 'client') {
        $sql = "UPDATE Public.client SET city_id = " . $city_id . " WHERE client_id = " . $_SESSION['user']['id'];
    } else {
        $sql = "UPDATE Public.autoservice SET city_id = " . $city_id . " WHERE autoservice_id = " . $_SESSION['user']['id'];
    }    
    $stmt = $pdo->exec($sql);
    $_SESSION['user']['city_id'] = $city_id;
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}
if ($_SESSION['user']['user_type'] == 'autoservice') { //адрес
    $address = htmlspecialchars($_POST['address']);
    $sql_address = $pdo->quote($address);
    $sql = "UPDATE Public.autoservice SET address = " . $sql_address . " WHERE autoservice_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql);
    $_SESSION['message']['text'] = 'Данные изменены успешно!';
    $_SESSION['message']['type'] = 'success';
}
if ($email != $_SESSION['user']['email']) { //смена почты через подтверждение
    email_check($email, $pdo);
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