<?php

session_start();

require_once 'connect.php';

$email = $_POST['email'];
$pass = $_POST['pass'];
$user_type = $_POST['user_type']; // 1 client 0 autoservice

if (empty($email) || empty($pass)) {
    $_SESSION['message'] = "Вы не ввели данные";
    header('Location: ../index.php');
} else {


    $sql = "SELECT * 
FROM Public.clients 
WHERE email = '$email'";

    $sql2 = "";

    if ($user_type) {
        $check_user = $pdo->query($sql);
    } else {
    }
    $result = $check_user->fetch();

    if (password_verify($pass, $result['pass']))
        echo "Вы успешно авторизировались!";
    else {
        $_SESSION['message'] = "Вы ввели неверный логин/пароль!";
        header('Location: ../index.php');
    }
}