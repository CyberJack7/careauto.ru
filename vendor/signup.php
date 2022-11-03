<?php

session_start();

require_once 'connect.php';
require_once 'path.php';


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
            header('Location: ../reg_page.php');
            exit;
        } else {
            continue;
        }
    }
    return;
}
function password_check($pass, $pass_confirm)
{
    if ($pass === $pass_confirm) {
        return;
    } else {
        $_SESSION['message'] = "Пароли не совпадают";
        header('Location: ../reg_page.php');
        exit;
    }
}
$user_type = $_POST['reg_button'];

if ($user_type == "client") { // для клиента
    $client = [
        "name" => $_POST['name_client'],
        "email" => $_POST['email'],
        "city_id" => $_POST['city_id'],
        "phone" => str_replace(['(', ')', '-', '+', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)
    ];
    password_check($_POST['password'], $_POST['password_confirm']);
    email_check($client['email'], $pdo);
    $sql = "INSERT INTO Public.client(name_client,phone_client,email_client,
        password_client,favorites,city_id) VALUES (:name_client,:phone,
        :email,:pass,:favorites,:city_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name_client' => $client['name'],
        'phone' => $client['phone'],
        'email' => $client['email'],
        'pass' => $client['password'],
        'favorites' => NULL,
        'city_id' => $client['city_id']
    ]);
    $_SESSION['message'] = "Регистрация прошла успешно!";
    header('Location: ../index.php');
} elseif ($user_type == "autoservice") { // для автосервиса
    $autoservice = [
        "name" => $_POST['name_autoservice'],
        "email" => $_POST['email'],
        "phone" => str_replace(['(', ')', '-', '+', ' '], '', $_POST['phone']),
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ];
    password_check($_POST['password'], $_POST['password_confirm']);
    email_check($autoservice['email'], $pdo);
    // пытаемся загрузить файл
    if (!move_uploaded_file($_FILES['document']['tmp_name'], $path_uploads_temp . time() . $_FILES['document']['name'])) {
        header('Location: ../reg_page.php');
        $_SESSION['message'] = "Ошибка при загрузке файла!";
    }
    $path_to_file = $path_uploads_temp . time() . $_FILES['document']['name'];
    print_r($autoservice);
    $sql = "INSERT INTO Public.autoservice_in_check(name_autoservice,email_autoservice,
        password_autoservice,phone_autoservice,document) VALUES (:name_autoservice,:email,
        :pass,:phone,:document)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name_autoservice' => $autoservice['name'],
        'phone' => $autoservice['phone'],
        'email' => $autoservice['email'],
        'pass' => $autoservice['password'],
        'document' => $path_to_file
    ]);
    $_SESSION['message'] = "Регистрация прошла успешно!";
    header('Location: ../index.php');
}