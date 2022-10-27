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
} elseif (empty($password)) {
    $_SESSION['message'] = "Введите пароль";
    header('Location: ../authoriz_page.php');
} else {

    $sql_admin = "SELECT * FROM public.admin 
        WHERE email_admin = '$email' AND password_admin = '$password'";

    $sql_client = "SELECT * FROM public.client 
        WHERE email_client = '$email' AND password_client = '$password'";

    $sql_autoservice = "SELECT * FROM public.autoservice 
        WHERE email_autoservice = '$email' AND password_autoservice = '$password'";

    $result = $pdo->query($sql_admin)->fetch();

    if($result){
        echo "Вы авторизованы как администратор!";
    } elseif ($user_type) {
        $result = $pdo->query($sql_client)->fetch();
        if($result){
            echo "Вы авторизованы как автовладелец!";
        } else {
            $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
            header('Location: ../index.php');
        }
    } else {
        $result = $pdo->query($sql_autoservice)->fetch();
        if($result){
            echo "Вы авторизованы как сервисный центр!";
        } else {
            $_SESSION['message'] = "Неверный логин или пароль! <br>
            Если данные введены верно, смените тип пользователя";
            header('Location: ../index.php');
        }
    }
}