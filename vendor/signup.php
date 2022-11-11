<?php

session_start();

require_once 'connect.php';
require_once 'path.php';
require_once 'send_email.php';

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
            $_SESSION['message'] = "Пользователь с таким email уже зарегистрирован!";
            header('Location: /reg_page.php');
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
        $_SESSION['message'] = "Пароли не совпадают";
        header('Location: /reg_page.php');
        exit;
    } elseif (strlen($pass) < 5 || strlen($pass) > 20) {
        $_SESSION['message'] = "Длина пароля должна быть от 5 до 20 символов <br> включительно";
        header('Location: /reg_page.php');
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
        "phone" => str_replace(['(', ')', '-', '+', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        "code" => send_email($_POST['email']),
        "attempt" => 3
    ];
    header('Location: /check_code.php');
    exit;
} elseif ($user_type == "autoservice") { // для автосервиса
    if (!move_uploaded_file($_FILES['document']['tmp_name'], $path_uploads_temp . time() . $_FILES['document']['name'])) {
        $_SESSION['message'] = "Ошибка при загрузке файла!";
        header('Location: /reg_page.php');
    }
    $path_to_file = $path_uploads_temp . time() . $_FILES['document']['name'];

    $_SESSION['new_user'] = [
        "type" => "autoservice",
        "name" => $_POST['name_autoservice'],
        "email" => $_POST['email'],
        "phone" => str_replace(['(', ')', '-', '+', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        "document" => $path_to_file,
        "code" => send_email($_POST['email']),
        "attempt" => 3
    ];
    header('Location: /check_code.php');
    exit;
}
    
    