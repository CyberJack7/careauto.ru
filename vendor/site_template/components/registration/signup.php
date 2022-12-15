<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
require_once PATH_QUERIES;
$pdo = conn();

function email_check($email, $pdo)
{
    $pdo = conn();
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
            header('Location: /registration/');
            exit;
        } else {
            continue;
        }
    }
    return;
}

function password_check($pass, $pass_confirm)
{
    if ($pass !== $pass_confirm) {
        $_SESSION['message']['text'] = "Пароли не совпадают";
        $_SESSION['message']['type'] = 'warning';
        header('Location: /registration/');
        exit;
    } elseif (strlen($pass) < 5 || strlen($pass) > 20) {
        $_SESSION['message']['text'] = "Длина пароля должна быть от 5 до 20 символов <br> включительно";
        $_SESSION['message']['type'] = 'warning';
        header('Location: /registration/');
        exit;
    } else {
        return;
    }
}
$user_type = $_POST['reg_button'];
password_check($_POST['password'], $_POST['password_confirm']);
email_check($_POST['email'], $pdo);
if ($user_type == "client") { // для клиента
    $_SESSION['new_user'] = [
        "type" => "client",
        "name" => $_POST['name_client'],
        "email" => $_POST['email'],
        "city_id" => $_POST['city_id'],
        "phone" => str_replace(['(', ')', '-', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        "code" => send_email($_POST['email']),
        "attempt" => 3
    ];
    header('Location: /confirmation_code/');
    exit;
} elseif ($user_type == "autoservice") { // для автосервиса
    $path_to_file = PATH_UPLOADS_TEMP . time() . '-' . translit($_FILES['document']['name']);
    if (!move_uploaded_file($_FILES['document']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path_to_file)) {
        $_SESSION['message']['text'] = "Не удалось загрузить файл!";
        $_SESSION['message']['type'] = 'warning';
        header('Location: /registration/');
        exit;
    }

    $_SESSION['new_user'] = [
        "type" => "autoservice",
        "name" => $_POST['name_autoservice'],
        "email" => $_POST['email'],
        "city_id" => $_POST['city_id'],
        "phone" => str_replace(['(', ')', '-', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        "document" => $path_to_file,
        "code" => send_email($_POST['email']),
        "attempt" => 3
    ];
    header('Location: /confirmation_code/');
    exit;
}