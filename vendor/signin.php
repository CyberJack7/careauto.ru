<?php

session_start();

require_once 'connect.php';

$email = $_POST['email'];
$pass = $_POST['pass'];

$sql = "SELECT * 
FROM Public.clients 
WHERE email = '$email'";

$check_user = $pdo->query($sql);

$result = $check_user->fetch();

if (password_verify($pass, $result['pass']))
    echo "Вы успешно авторизировались!";
else {
    $_SESSION['message'] = "Вы ввели неверный логин/пароль!";
    header('Location: ../index.php');
}
