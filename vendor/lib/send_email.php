<?php

function send_email($email_to, $message_type='reg')
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $permitted_chars[rand(0, 66)];
    }
    
    if ($message_type == 'reg') {
        $subject = 'Подтверждение регистрации careauto.ru';
        $message = "Привет!<br>Регистрация на платформе careauto.ru<br>
        Твой код для подтверждения аккаунта: <b>$code</b><br>
        Если ты не понимаешь, о чем идет речь, просто проигнорируй это письмо :)";
    } else {
        $subject = 'Удаление аккаунта careauto.ru';
        $message = "Привет!<br>Ты решил покинуть нашу платформу careauto.ru, в связи с чем твой аккаунт был удалён<br>
        Нам будет тебя не хватать :(<br>
        Но мы всегда будем рады, если ты решишь вернуться!";
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