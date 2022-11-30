<?php

function send_email($email_to, $message_type = 'reg')
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $permitted_chars[rand(0, 66)];
    }

    if ($message_type == 'reg') {
        $subject = 'Подтверждение регистрации careauto.ru';
        $message = "Привет!<br>Регистрация на платформе careauto.ru<br>
        Ваш код для подтверждения аккаунта: <b>$code</b><br>
        Если вы не понимаете, о чем идет речь, просто проигнорируйте это письмо и ни в коем случае не пересылайте его никому!";
    } elseif ($message_type == 'reg_complete') {
        $subject = 'Регистрация СЦ careauto.ru';
        $message = "Привет!<br>Регистрация на платформе careauto.ru<br>
        Ваш сервис прошел проверку и успешно зарегистрирован на нашем портале.
        <br> Для входа используйте почту и пароль указанную при регистрации!";
    } elseif ($message_type == 'reg_fail') {
        $subject = 'Регистрация СЦ careauto.ru';
        $message = "Привет!<br>Регистрация на платформе careauto.ru<br>
        Ваш сервис, к сожалению, не прошел проверку и не был зарегистрирован на нашем портале.
        <br> Данные указаные при регистрации оказались некорректны! Попробуйте еще раз введя корректные данные.";
    } else {
        $subject = 'Удаление аккаунта careauto.ru';
        $message = "Привет<br>Мы огорчены вашим решением покинуть нашу платформу careauto.ru<br>
        Ваш аккаунт со всеми данными был удалён<br>
        Нам будет вас не хватать :(<br>
        Но мы всегда будем рады, если вы решите вернуться!";
    }

    $subject = '=?utf-8?b?' . base64_encode($subject) . '?=';
    $fromMail = 'autocare.ru@mail.ru'; //почта, с которой отправляешь
    $fromName = 'careauto.ru';
    $date = date(DATE_RFC2822);
    $messageId = '<' . time() . '-' . md5($fromMail . $email_to) . '@' . $_SERVER['SERVER_NAME'] . '>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
    $headers .= "From: " . $fromName . " <" . $fromMail . "> \r\n";
    $headers .= "Date: " . $date . " \r\n";
    $headers .= "Message-ID: " . $messageId . " \r\n";
    mail($email_to, $subject, $message, $headers);
    return $code;
}