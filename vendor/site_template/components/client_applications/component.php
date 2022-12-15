<?php
date_default_timezone_set('Europe/Moscow');

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;
require_once PATH_SEND_EMAIL;

$pdo = conn();


//отмена заявки
if (isset($_POST['delete_application'])) {
    $application_id = json_decode($_POST['delete_application'], true);
    $sql_delete_application = "DELETE FROM public.application WHERE application_id = " . $application_id;
    $stmt = $pdo->exec($sql_delete_application);
}


//переход к оплате заявки
if (isset($_POST['pay_application'])) {
    $application = json_decode($_POST['pay_application'], true);
    $template = '<div class="container content column" style="display: flex">
            <div class="panel" id="client_id_' . $application['client_id'] . '">
                <h1>Оплата услуг</h1>
                <div class="line">
                    <div class="mb-3">
                        <label class="form-label" for="card_number">Номер карты</label>
                        <input class="form-control" id="card_number" type="text" placeholder="XXXX-XXXX-XXXX-XXXX"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="validity_date">Срок действия</label>
                        <input class="form-control" id="validity_date" type="text" placeholder="ММ/ГГ"/>
                    </div>
                </div>
                <div class="line">
                    <div class="mb-3">
                        <label class="form-label" for="cardholder_name">Имя и фамилия владельца карты</label>
                        <input class="form-control" id="cardholder_name" type="text" placeholder="Латинскими буквами"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="cvc_cvv">CVC/CVV</label>
                        <input class="form-control" id="cvc_cvv" type="password" placeholder="CVC/CVV"/>
                    </div>
                </div>
                <div class="btn_div">
                    <button class="btn btn-primary" id="pay_button_' . $application['application_id'] . '" type="button" onclick="submitPayApplication(this)">Оплатить</button>
                    <button class="btn btn-secondary" id="cancel_pay_button" type="button" onclick="cancelPayApplication()">Отменить</button>
                </div>
            </div>
        </div>';
    echo $template;
}


//оплата заявки
if (isset($_POST['submit_pay_application'])) {
    $application_pay = json_decode($_POST['submit_pay_application'], true);
    $sql_user_requisites = "SELECT * FROM public.user_requisites WHERE client_id = " . $application_pay['client_id'];
    $user_requisites = $pdo->query($sql_user_requisites)->fetch();
    $valid = 0; //всё в порядке
    if (!empty($user_requisites)) {
        $sql_price = "SELECT price FROM public.application WHERE application_id = " . $application_pay['application_id'];
        $application_price = $pdo->query($sql_price)->fetch()['price'];
        if ($application_price > $user_requisites['fund_balance']) {
            $valid = 2; //недостаточно средств
        }
        foreach($application_pay as $key => $item) {
            if ($key == 'cardholder_name') {
                if ($user_requisites[$key] != '' && $item != mb_strtolower($user_requisites[$key])) {
                    $valid = 1; //данные неверны
                    break;
                }
            } else if($key != 'client_id' && $key != 'application_id' && $item != $user_requisites[$key]) {
                $valid = 1; //данные неверны
                break;
            }
        }
        if ($valid == 0) {
            $sql_fund_balance = "UPDATE public.user_requisites SET fund_balance = " . ($user_requisites['fund_balance'] - $application_price) . " 
                WHERE client_id = " . $application_pay['client_id'];
            $date_payment = date('Y-m-d H:i:s');
            $sql_date_payment = "UPDATE public.application SET date_payment = " . $pdo->quote($date_payment) . " 
                WHERE application_id = " . $application_pay['application_id'];
            $stmt = $pdo->exec($sql_fund_balance);
            $stmt = $pdo->exec($sql_date_payment);
            $sql_name_autoservice = "SELECT autoservice_id, name_autoservice FROM public.autoservice JOIN public.application USING(autoservice_id) 
                WHERE application_id = " . $application_pay['application_id'];
            $name_autoservice = $pdo->query($sql_name_autoservice)->fetch()['name_autoservice'];
            $requisites = getRequisitesInfo($pdo->query($sql_name_autoservice)->fetch()['autoservice_id']);
            $check = [
                'name_autoservice' => $name_autoservice,
                'application_id' => $application_pay['application_id'],
                'price' => $application_price,
                'date_payment' => $date_payment,
                'card_number' => $user_requisites['card_number'],
                'inn' => $requisites['inn'],
                'kpp' => $requisites['kpp'],
                'bik' => $requisites['bik'],
                'check_acc' => $requisites['check_acc'],
                'corr_acc' => $requisites['corr_acc']
            ];
            $sql_email_client = "SELECT email_client FROM public.client WHERE client_id = " . $application_pay['client_id'];
            $email_client = $pdo->query($sql_email_client)->fetch()['email_client'];
            send_email($email_client, 'payment_check', $check);
        }
    } else {
        $valid = 3; //карты не существует
    }
    echo json_encode($valid);
}