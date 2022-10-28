<?php

session_start();

require_once 'connect.php';

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$pass_confirm = $_POST['pass_confirm'];

$sql = "SELECT * 
FROM Public.clients 
WHERE email = '$email'";

$check_user = $pdo->query($sql);

if ($check_user->fetchColumn() > 0) {
    $_SESSION['message'] = "Пользователь с таким email уже зарегистрирован!";
    header('Location: ../reg_page.php');
} else {

    if ($pass === $pass_confirm) {
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Public.clients VALUES (DEFAULT,'$email','$pass_hash')";
        $pdo->exec($sql);
        $_SESSION['message'] = "Регистрация прошла успешно!";
        header('Location: ../index.php');
    } else {
        $_SESSION['message'] = "Пароли не совпадают";
        header('Location: ../reg_page.php');
    }
}
