<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

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
        foreach($application_pay as $key => $item) {
            if($key != 'client_id' && $key != 'application_id' && $item != mb_strtolower($user_requisites[$key])) {
                $valid = 1; //данные неверны
                break;
            }
        }
        $sql_price = "SELECT price FROM public.application WHERE application_id = " . $application_pay['application_id'];
        $application_price = $pdo->query($sql_price)->fetch()['price'];
        if ($application_price > $user_requisites['fund_balance']) {
            $valid = 2; //недостаточно средств
        }
        if ($valid == 0) {
            $sql_fund_balance = "UPDATE public.user_requisites SET fund_balance = " . ($user_requisites['fund_balance'] - $application_price) . " WHERE client_id = " . $application_pay['client_id'];
            $sql_date_payment = "UPDATE public.application SET date_payment = " . $pdo->quote(date('Y-m-d h:i:s')) . " WHERE application_id = " . $application_pay['application_id'];
            $stmt = $pdo->exec($sql_fund_balance);
            $stmt = $pdo->exec($sql_date_payment);
        }
    } else {
        $valid = 3; //карты не существует
    }
    echo json_encode($valid);
}