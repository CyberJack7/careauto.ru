<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
$pdo = conn();

$email = htmlspecialchars($_POST['email']);
$password = $_POST['password'];
$user_type = $_POST['user_type']; // 1 - client, 0 - autoservice

$sql_admin = "SELECT * FROM public.admin WHERE email_admin = '" . $email . "'";

$sql_client = "SELECT * FROM public.client WHERE email_client = '" . $email . "'";

$sql_autoservice = "SELECT * FROM public.autoservice WHERE email_autoservice = '" . $email . "'";

$result = $pdo->query($sql_admin)->fetch();

if (password_verify($password, $result['password_admin'])) {
    $_SESSION['user'] = [
        "user_type" => "admin",
        "id" => $result['admin_id'],
        "name" => $result['name_admin'],
        "email" => $result['email_admin']
    ];
    $_SESSION['message'] = "Вы авторизованы как администратор!";
    header('Location: /');
} elseif ($user_type) { //client
    $result = $pdo->query($sql_client)->fetch();
    if (password_verify($password, $result['password_client'])) {
        $user_id = $result['client_id'];
        $sql_ban_client = "SELECT * FROM public.ban_list
        WHERE user_id = '$user_id'";
        $ban_result = $pdo->query($sql_ban_client)->fetch();
        if (!empty($ban_result)) {
            $_SESSION['message'] = "Данный аккаунт заблокирован " .  $ban_result['date'] .
                " по причине: " . $ban_result['text'];
            header('Location: /authorization/');
            exit;
        }
        $_SESSION['user'] = [
            "user_type" => "client",
            "id" => $result['client_id'],
            "name" => $result['name_client'],
            "email" => $result['email_client'],
            "phone" => $result['phone_client'],
            "city_id" => $result['city_id']
        ];
        $_SESSION['message'] = "Вы авторизованы как автовладелец!";
        header('Location: /my_auto/');
    } else {
        $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
        header('Location: /authorization/');
    }
} else { //autoservice
    $result = $pdo->query($sql_autoservice)->fetch();
    if (password_verify($password, $result['password_autoservice'])) {
        $user_id = $result['autoservice_id'];
        $sql_ban_client = "SELECT * FROM public.ban_list
        WHERE user_id = '$user_id'";
        $ban_result = $pdo->query($sql_ban_client)->fetch();
        if (!empty($ban_result)) {
            $_SESSION['message'] = "Данный аккаунт заблокирован " .  $ban_result['date'] .
                " по причине: " . $ban_result['text'];
            header('Location: /authorization/');
            exit;
        }
        $_SESSION['user'] = [
            "user_type" => "autoservice",
            "id" => $result['autoservice_id'],
            "name" => $result['name_autoservice'],
            "email" => $result['email_autoservice'],
            "phone" => $result['phone_autoservice'],
            "city_id" => $result['city_id']
        ];
        $_SESSION['message'] = "Вы авторизованы как сервисный центр!";
        header('Location: /autoservice_applications/');
    } else {
        $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
        header('Location: /authorization/');
    }
}