<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
$pdo = conn();

if (empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}

if ($_SESSION['user']['user_type'] == 'client') {
    $sql_client_delete = "DELETE FROM public.client WHERE client_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql_client_delete);
    send_email($_SESSION['user']['email'], 'del');
    $_SESSION['message']['text'] = 'Аккаунт успешно удалён!';
    $_SESSION['message']['type'] = 'info';
    unset($_SESSION['user']);
} elseif ($_SESSION['user']['user_type'] == 'autoservice') {
    $sql_autoservice_delete = "DELETE FROM public.autoservice WHERE autoservice_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql_autoservice_delete);
    send_email($_SESSION['user']['email'], 'del');
    $_SESSION['message']['text'] = 'Аккаунт успешно удалён!';
    $_SESSION['message']['type'] = 'info';
    unset($_SESSION['user']);
}

