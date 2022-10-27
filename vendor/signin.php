<?php

session_start();

require_once 'connect.php';

$email = $_POST['email'];
$password = md5($_POST['password']);
$user_type = $_POST['user_type']; // 1 - client, 0 - autoservice

echo $password;

if (empty($email)) {
    $_SESSION['message'] = "Введите почту";
    header('Location: ../authoriz_page.php');
    $_SESSION['result'] = 1;
} elseif (empty($_POST['password'])) {
    $_SESSION['message'] = "Введите пароль";
    header('Location: ../authoriz_page.php');
    $_SESSION['result'] = 1;
} else {

    $sql_admin = "SELECT * FROM public.admin 
        WHERE email_admin = '$email' AND password_admin = '$password'";

    $sql_client = "SELECT * FROM public.client 
        WHERE email_client = '$email' AND password_client = '$password'";

    $sql_autoservice = "SELECT * FROM public.autoservice 
        WHERE email_autoservice = '$email' AND password_autoservice = '$password'";

    $result = $pdo->query($sql_admin)->fetch();

    if($result){
        $_SESSION['user'] = [
            "user_type" => "admin",
            "id" => $result['admin_id'],
            "name" => $user['name_admin'],
            "email" => $user['email_admin']
        ];
        $_SESSION['message'] = "Вы авторизованы как администратор!";
        $_SESSION['result'] = 2;
        header('Location: ../index.php');
    } elseif ($user_type) {
        $result = $pdo->query($sql_client)->fetch();
        if($result){
            $_SESSION['user'] = [
                "user_type" => "client",
                "id" => $result['client_id'],
                "name" => $user['name_client'],
                "email" => $user['email_client'],
                "phone" => $user['phone_client'],
                "city_id" => $user['city_id']
            ];
            $_SESSION['message'] = "Вы авторизованы как автовладелец!";
            $_SESSION['result'] = 2;
            header('Location: ../index.php');
        } else {
            $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
            $_SESSION['result'] = 1;
            header('Location: ../authoriz_page.php');
        }
    } else {
        $result = $pdo->query($sql_autoservice)->fetch();
        if($result){
            $_SESSION['user'] = [
                "user_type" => "autoservice",
                "id" => $result['autoservice_id'],
                "name" => $user['name_autoservice'],
                "email" => $user['email_autoservice'],
                "phone" => $user['phone_autoservice'],
                "city_id" => $user['city_id']               
            ];
            $_SESSION['message'] = "Вы авторизованы как сервисный центр!";
            $_SESSION['result'] = 2;
            header('Location: ../index.php');
        } else {
            $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
            $_SESSION['result'] = 1;
            header('Location: ../authoriz_page.php');
        }
    }
}
?>