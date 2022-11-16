<?php
session_start();

//на страницу сохранения инфы может переадресовываться только авторизованный пользователь
if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
$pdo = conn();

$password = htmlspecialchars($_POST['current_password']);
$new_password = htmlspecialchars($_POST['new_password']);
$conf_new_password = htmlspecialchars($_POST['conf_new_password']);

$sql_client = "SELECT password_client FROM public.client WHERE client_id = " . $_SESSION['user']['id'];

$sql_autoservice = "SELECT password_autoservice FROM public.autoservice WHERE autoservice_id = " . $_SESSION['user']['id'];

if ($_SESSION['user']['user_type'] == 'client') { //автовладелец
    $result = $pdo->query($sql_client)->fetch();
    if (password_verify($password, $result['password_client'])) { //проверка текущего пароля
        if ($new_password === $conf_new_password) { //проверка соответствий нового пароля
            $sql_new_password = $pdo->quote(password_hash($new_password, PASSWORD_DEFAULT));
            $sql = "UPDATE public.client SET password_client = " . $sql_new_password . " WHERE client_id = " . $_SESSION['user']['id'];
            $stmt = $pdo->exec($sql);
            $_SESSION['message']['text'] = 'Пароль изменён успешно!';
            $_SESSION['message']['type'] = 'success';
        } else {
            $_SESSION['message']['text'] = 'Пароли не совпадают';
            $_SESSION['message']['type'] = 'warning';
        }
    } else {
        $_SESSION['message']['text'] = 'Введён неверный текущий пароль';
        $_SESSION['message']['type'] = 'warning';
    }
} else { //автосервис
    $result = $pdo->query($sql_autoservice)->fetch();
    if (password_verify($password, $result['password_autoservice'])) { //проверка текущего пароля
        if ($new_password === $conf_new_password) { //проверка соответствий нового пароля
            $sql_new_password = $pdo->quote(password_hash($new_password, PASSWORD_DEFAULT));
            $sql = "UPDATE public.autoservice SET password_autoservice = " . $sql_new_password . " WHERE autoservice_id = " . $_SESSION['user']['id'];
            $stmt = $pdo->exec($sql);
            $_SESSION['message']['text'] = 'Пароль изменён успешно!';
            $_SESSION['message']['type'] = 'success';
        } else {
            $_SESSION['message']['text'] = 'Пароли не совпадают';
            $_SESSION['message']['type'] = 'warning';
        }
    } else {
        $_SESSION['message']['text'] = 'Введён неверный текущий пароль';
        $_SESSION['message']['type'] = 'warning';
    }
}
header('Location: /profile/');
exit;