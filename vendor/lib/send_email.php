<?php

function send_email($email_to, $message_type = 'reg', $check = NULL)
{
    $permitted_chars = '0123456789';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $permitted_chars[rand(0, 9)];
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
    } elseif ($message_type == "password_recovery") {
        $subject = 'Восстановление пароля careauto.ru';
        $message = "Привет!<br>Восстановление пароля на платформе careauto.ru<br>
        Ваш аккаунт начал процедуру восстановления пароля. Ваш код для восстановления пароля: <b>$code</b><br>
        Если вы не понимаете, о чем идет речь, просто проигнорируйте это письмо и ни в коем случае не пересылайте его никому!";
    } elseif ($message_type == "payment_check"){
        $subject = 'Оплата услуг careauto.ru';
        $message = "Привет!<br>Оплата услуг сервисного центра <b>" . $check['name_autoservice'] . "</b> прошла успешно<br>
        <b>Электронный чек №" . $check['application_id'] . ":</b><br>
        Сумма заказа: " . $check['price'] . " р<br>
        Дата платежа: " . $check['date_payment'] . "<br>
        Данные плательщика:<br>
        Номер карты: " . $check['card_number'] . "<br>
        Данные получателя:<br>
        ИНН: " . $check['inn'] . "<br>
        КПП: " . $check['kpp'] . "<br>
        БИК: " . $check['bik'] . "<br>
        Расчётный счёт: " . $check['check_acc'] . "<br>
        Корреспондентский счёт: " . $check['corr_acc'] . "<br>
        ";
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