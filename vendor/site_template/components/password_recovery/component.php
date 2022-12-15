<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
function check_email($email)
{
    $pdo = conn();
    $sql_check_client = "SELECT client_id FROM Public.client
    WHERE email_client=" . $pdo->quote($email);
    $sql_check_autoservice = "SELECT autoservice_id FROM Public.autoservice
    WHERE email_autoservice=" . $pdo->quote($email);
    $res_client = $pdo->query($sql_check_client)->fetch();
    $res_autoservice = $pdo->query($sql_check_autoservice)->fetch();
    if (!$res_client and !$res_autoservice) {
        $_SESSION['message']['type'] = "warning";
        $_SESSION['message']['text'] = "Аккаунт с таким email не существует!";
        header('Location: /password_recovery/');
        return;
    } elseif ($res_client != false) {
        $usertype = "client"; // Тип юзера клиент
    } else {
        $usertype = "autoservice"; // Тип юзера автосервис
    }
    $code = send_email($email, "password_recovery");
    $_SESSION['password_recovery']['attempt'] = 2;
    $_SESSION['password_recovery']['type'] = $usertype;
    $_SESSION['password_recovery']['email'] = $email;
    $_SESSION['password_recovery']['code'] = $code;
    $_SESSION['password_recovery']['valid'] = false;
    header('Location: /confirmation_code/');
    return;
}

function change_password($password, $password_confirm)
{
    $pdo = conn();
    if ($password == $password_confirm) {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        if ($_SESSION['password_recovery']['type'] == "client") {
            $sql = "UPDATE Public.client SET password_client=" . "'" . $hash_password . "'" .
                " WHERE email_client=" . $pdo->quote($_SESSION['password_recovery']['email']);
        } else {
            $sql = "UPDATE Public.autoservice SET password_autoservice="  . "'" . $hash_password . "'" .
                " WHERE email_autoservice=" . $pdo->quote($_SESSION['password_recovery']['email']);
        }
        $res = $pdo->exec($sql);
        $_SESSION['message']['type'] = "success";
        $_SESSION['message']['text'] = "Вы успешно изменили пароль!";
        unset($_SESSION['password_recovery']);
        header('Location: /authorization/');
    } else {
        $_SESSION['message']['type'] = "warning";
        $_SESSION['message']['text'] = "Пароли не совпадают!";
        header('Location: /password_recovery/');
    }
}

if (!empty($_POST['email'])) {
    check_email($_POST['email']);
}
if (!empty($_POST['password']) and !empty($_POST['password_confirm'])) {
    change_password($_POST['password'], $_POST['password_confirm']);
}