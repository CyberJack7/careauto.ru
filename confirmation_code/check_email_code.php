<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_SEND_EMAIL;
require_once PATH_CONNECT;


$code_inp = $_POST['code']; //введённый пользователем код подтверждения

if (!empty($_SESSION['new_user'])) { //подтверждение регистрации новым пользователем
    if ($_POST['resend']) { //если повторная отправка кода подтверждения
        $_SESSION['new_user']['code'] = send_email($_SESSION['new_user']['email']);
        $_SESSION['message']['text'] = "Отправлен новый код!";
        $_SESSION['message']['type'] = 'info';
        header('Location: /confirmation_code/');
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
                unset($_SESSION['new_user']);
                $_SESSION['message']['text'] = "Регистрация прошла успешно!";
                $_SESSION['message']['type'] = 'success';
                header('Location: /authorization/');
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
                unset($_SESSION['new_user']);
                $_SESSION['message']['text'] = "Регистрация прошла успешно!";
                $_SESSION['message']['type'] = 'success';
                header('Location: /authorization/');
                exit;
            }
        } else {
            $_SESSION['new_user']['attempt'] -= 1;
            if ($_SESSION['new_user']['attempt'] == 1) {
                $_SESSION['message']['text'] = "Код введен неверно! У вас осталось " . $_SESSION['new_user']['attempt'] . " попытка";
                $_SESSION['message']['type'] = 'warning';
            }
            else {
                $_SESSION['message']['text'] = "Код введен неверно! У вас осталось " . $_SESSION['new_user']['attempt'] . " попытки";
                $_SESSION['message']['type'] = 'warning';
            }
            header('Location: /confirmation_code/');
            exit;
        }
    } else {
        unset($_SESSION['new_user']);
        $_SESSION['message']['text'] = "Превышено число попыток ввода кода!<br>Выполните регистрацию повторно!";
        $_SESSION['message']['type'] = 'danger';
        header('Location: /registration/');
        exit;
    }
} elseif (!empty($_SESSION['user'])) { //если пользователь меняет почту
    if ($_POST['resend']) { //если повторная отправка кода подтверждения
        $_SESSION['user']['code'] = send_email($_SESSION['user']['new_email']);
        $_SESSION['message']['text'] = "Отправлен новый код!";
        $_SESSION['message']['type'] = 'info';
        header('Location: /confirmation_code/');
        exit;
    }
    if ($_SESSION['user']['attempt'] > 1) { // 3 попытки на ввод кода!
        if ($_SESSION['user']['code'] === $code_inp) { // если код введен верно
            $pdo = conn();
            $email = $_SESSION['user']['new_email'];
            $sql_email = $pdo->quote($email);
            if ($_SESSION['user']['user_type'] == 'client') {
                $sql = "UPDATE Public.client SET email_client = " . $sql_email . " WHERE client_id = " . $_SESSION['user']['id'];
            } else {
                $sql = "UPDATE Public.autoservice SET email_autoservice = " . $sql_email . " WHERE autoservice_id = " . $_SESSION['user']['id'];
            }
            $stmt = $pdo->exec($sql);
            $_SESSION['user']['email'] = $email;
            $_SESSION['message']['text'] = 'Данные изменены успешно!';
            $_SESSION['message']['type'] = 'success';
            unset($_SESSION['user']['new_email'], $_SESSION['user']['code'], $_SESSION['user']['attempt']);
            header('Location: /profile/');
            exit;
        } else { //если код неверный
            $_SESSION['user']['attempt'] -= 1;
            if ($_SESSION['user']['attempt'] == 1) {
                $_SESSION['message']['text'] = "Код введен неверно! У вас осталось " . $_SESSION['user']['attempt'] . " попытка";
                $_SESSION['message']['type'] = 'warning';
            }
            else {
                $_SESSION['message']['text'] = "Код введен неверно! У вас осталось " . $_SESSION['user']['attempt'] . " попытки";
                $_SESSION['message']['type'] = 'warning';
            }
            header('Location: /confirmation_code/');
            exit;
        }
    } else {
        unset($_SESSION['user']['new_email'], $_SESSION['user']['code'], $_SESSION['user']['attempt']);
        $_SESSION['message']['text'] = "Превышено число попыток ввода кода подтверждения!<br>Попробуйте снова";
        $_SESSION['message']['type'] = 'danger';
        header('Location: /profile/');
        exit;
    }
}