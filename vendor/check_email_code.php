<?php
session_start();
require_once 'send_email.php';
require_once 'connect.php';
$code_inp = $_POST['code'];
if ($_POST['resend']) {
    $_SESSION['new_user']['code'] = send_email($_SESSION['new_user']['email']);
    $_SESSION['message'] = "Отправлен новый код!";
    header('Location: /check_code.php');
    exit;
}
if ($_SESSION['new_user']['attempt'] > 1) { // 3 попытки на ввод кода!
    if ($_SESSION['new_user']['code'] === $code_inp) { // если код введен верно
        $pdo = conn();
        if ($_SESSION['new_user']['type'] == "client") { // если пользователь - клиент
            $sql = "INSERT INTO Public.client(name_client,phone_client,email_client,
              password_client,favorites,city_id) VALUES (:name_client,:phone,
             :email,:pass,:favorites,:city_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name_client' => $_SESSION['new_user']['name'],
                'phone' => $_SESSION['new_user']['phone'],
                'email' => $_SESSION['new_user']['email'],
                'pass' => $_SESSION['new_user']['password'],
                'favorites' => NULL,
                'city_id' => $_SESSION['new_user']['city_id']
            ]);
            $_SESSION['message'] = "Регистрация прошла успешно!";
            header('Location: /authoriz_page.php');
            exit;
        } elseif ($_SESSION['new_user']['type'] == "autoservice") { //автосервис
            $sql = "INSERT INTO Public.autoservice_in_check(name_autoservice,email_autoservice,
            password_autoservice,phone_autoservice,document) VALUES (:name_autoservice,:email,
            :pass,:phone,:document)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name_autoservice' => $_SESSION['new_user']['name'],
                'phone' => $_SESSION['new_user']['phone'],
                'email' => $_SESSION['new_user']['email'],
                'pass' => $_SESSION['new_user']['password'],
                'document' => $_SESSION['new_user']['document']
            ]);
            $_SESSION['message'] = "Регистрация прошла успешно!";
            header('Location: /authoriz_page.php');
            exit;
        }
    } else {
        $_SESSION['new_user']['attempt'] -= 1;
        if ($_SESSION['new_user']['attempt'] == 1)
            $_SESSION['message'] = "Код введен не верно! У вас осталось " . $_SESSION['new_user']['attempt'] . " попытка";
        else
            $_SESSION['message'] = "Код введен не верно! У вас осталось " . $_SESSION['new_user']['attempt'] . " попытки";
        header('Location: /check_code.php');
        exit;
    }
} else {
    $_SESSION['message'] = "Превышено число попыток ввода кода!Регистрируйтесь заново!";
    header('Location: /reg_page.php');
    exit;
}