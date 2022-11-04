<?php

function send_email($email_to) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i=0; $i<6; $i++) {
        $code .= $permitted_chars{rand(0, 66)};
    }
    $subject = 'Подтверждение регистрации careauto.ru';
    $message = "Привет!<br>Регистрация на платформе careauto.ru<br>
    Твой код для подтверждения аккаунта: <b>$code</b><br>
    Если ты не понимаешь, о чем идет речь, просто проигнорируй это письмо :)";    

    $subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
    $fromMail = ''; //почта, с которой отправляешь
    $fromName = 'careauto.ru';
    $date = date(DATE_RFC2822);
    $messageId='<'.time().'-'.md5($fromMail.$email_to).'@'.$_SERVER['SERVER_NAME'].'>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "Content-type: text/html; charset=utf-8". "\r\n";
    $headers .= "From: ". $fromName ." <". $fromMail ."> \r\n";
    $headers .= "Date: ". $date ." \r\n";
    $headers .= "Message-ID: ". $messageId ." \r\n";
    
    mail($email_to, $subject, $message, $headers);
}

send_email(); //передать почту с формы