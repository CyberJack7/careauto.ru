<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;
$pdo = conn();

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$user_type = $_POST['user_type']; // 1 - client, 0 - autoservice

$sql_admin = "SELECT * FROM public.admin WHERE email_admin = '" . $email . "'";

$sql_client = "SELECT * FROM public.client WHERE email_client = '" . $email . "'";

$sql_autoservice = "SELECT * FROM public.autoservice WHERE email_autoservice = '" . $email . "'";

$result = $pdo->query($sql_admin)->fetch();

$_SESSION['not_auth_user']['attempt']--;
if ($_SESSION['not_auth_user']['attempt'] == 2) {
    $_SESSION['message']['text'] = "Неверный логин или пароль! <br>
    Если данные введены верно, смените тип пользователя. Осталось <b>две</b> попытки.";
    $_SESSION['message']['type'] = 'warning';
} elseif ($_SESSION['not_auth_user']['attempt'] == 1) {
    $_SESSION['message']['text'] = "Неверный логин или пароль! <br>
    Если данные введены верно, смените тип пользователя. Осталась <b>одна</b> попытка.";
    $_SESSION['message']['type'] = 'warning';
} else {
    $_SESSION['message']['text'] = "Неверный логин или пароль! Вы потратили все попытки. Вход заблокирован на 30 секунд!";
    $_SESSION['message']['type'] = 'danger';
}

if (password_verify($password, $result['password_admin'])) {
    $_SESSION['user'] = [
        "user_type" => "admin",
        "id" => $result['admin_id'],
        "name" => $result['name_admin'],
        "email" => $result['email_admin']
    ];
    header('Location: /');
} elseif ($user_type) { //client
    $result = $pdo->query($sql_client)->fetch();
    if (password_verify($password, $result['password_client'])) {
        $user_id = $result['client_id'];
        getUserBanInfoById($user_id);
        $_SESSION['user'] = [
            "user_type" => "client",
            "id" => $result['client_id'],
            "name" => $result['name_client'],
            "email" => $result['email_client'],
            "phone" => $result['phone_client'],
            "city_id" => $result['city_id']
        ];
        $_SESSION['message']['text'] = "Вы авторизованы как автовладелец!";
        $_SESSION['message']['type'] = 'success';
        unset($_SESSION['not_auth_user']['attempt']);
        header('Location: /my_auto/');
    } else {
        header('Location: /authorization/');
    }
} else { //autoservice
    $result = $pdo->query($sql_autoservice)->fetch();
    if (password_verify($password, $result['password_autoservice'])) {
        $user_id = $result['autoservice_id'];
        getUserBanInfoById($user_id);
        $_SESSION['user'] = [
            "user_type" => "autoservice",
            "id" => $result['autoservice_id'],
            "name" => $result['name_autoservice'],
            "email" => $result['email_autoservice'],
            "phone" => $result['phone_autoservice'],
            "city_id" => $result['city_id']
        ];
        $_SESSION['message']['text'] = "Вы авторизованы как сервисный центр!";
        $_SESSION['message']['type'] = 'success';
        unset($_SESSION['not_auth_user']['attempt']);
        header('Location: /autoservice_applications/');
    } else {
        header('Location: /authorization/');
    }
}