<?php
if (!isset($_SESSION['user']['id'])) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/queries.php';
require_once PATH_CONNECT;


function get_service($category_id)
{
    $arService = getServiceList($category_id, $_SESSION['user']['id']);
    foreach ($arService as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
}

function get_autoserv_service($autoservice_id, $category_id)
{
    $arService = getAutoserviceServiceList($autoservice_id, $category_id);
    foreach ($arService as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
}
function service_info($autoservice_id, $service_id)
{

    $arService = getServiceInfo($autoservice_id, $service_id);
    if ($arService == NULL) {
        $arService = [];
        $arService['price'] = '';
        $arService['text'] = '';
        $arService['certification'] = '';
        echo '<div id="con2" class="con2"> Стоимость услуги(обязательно): ' . '<input name="add_price" id="add_price" class="form-control" type="number" value="' . $arService['price'] . '" required>' .
            'Описание услуги(необязательно): ' . '<textarea class="form-control" readonly id="text" name="text" type="textarea" maxlength="200" placeholder="Описание услуги" style="max-height: 100px"></textarea>' .
            'Сертификация(необязательно): ' .
            '<input name="add_certification" id="add_certification" accept="application/pdf" class="form-control" type="file">
        <button id="add_service" type="button" class="btn btn-outline-primary">Добавить услугу</button>
        </div>';
    } else {
        echo '<div id="con2" class="con2"> Стоимость услуги: ' . '<input name="price" id="price" class="form-control-plaintext " type="number" value="' . $arService['price'] . '" readonly required>'  .
            'Описание услуги: ' . '<textarea class="form-control" id="text1" name="text1" type="textarea" maxlength="200" placeholder="Описание услуги" style="max-height: 100px">' . $arService['text'] . '</textarea>' .
            'Сертификация: ' . '<a id="link" target="_blank" href="' . $arService['certification'] . '">' . mb_substr($arService['certification'], 1 + strpos($arService['certification'], '-')) . '</a>
        <input disabled name="certification" id="certification" accept="application/pdf" class="form-control" type="file">
        <button id="edit" type="button" class="btn btn-outline-primary" status="off">Редактировать</button>
        <button id="del_service" value="' . $service_id . '" type="button" class="btn btn-outline-danger">Удалить</button>
        </div>';
    }
}

function service_update($autoservice_id, $service_id, $price, $text, $certification = NULL)
{
    $pdo = conn();
    if ($certification != NULL) {
        $sql_get_old_file = "SELECT certification FROM Public.autoservice_service
        WHERE autoservice_id=" . $autoservice_id . "AND service_id=" . $service_id;
        $res_file = $pdo->query($sql_get_old_file)->fetch();
        $directory = PATH_UPLOADS_REGULAR . $autoservice_id . '/certification/';
        $name_cert = time() . '_' . $service_id . '-' . translit($_FILES['certification']['name']);
        $full_path = $_SERVER['DOCUMENT_ROOT'] . $directory . $name_cert;

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $directory))
            mkdir($_SERVER['DOCUMENT_ROOT'] . $directory, 0777, true);
        if (!move_uploaded_file($_FILES['certification']['tmp_name'], $full_path)) {
            $_SESSION['message']['text'] = "Не удалось загрузить файл, попробуйте снова";
            $_SESSION['message']['type'] = 'warning';
            header('Location: /autoservice_service/');
            exit;
        }
        $sql = "UPDATE Public.autoservice_service 
        SET price=" . $pdo->quote($price) .
            ",text=" . $pdo->quote($text) .
            ",certification=" . $pdo->quote($directory . $name_cert) .
            "WHERE autoservice_id=" . $autoservice_id .
            "AND service_id=" . $service_id;
        $res = $pdo->exec($sql);
        if ($res_file['certification'] != NULL)
            unlink($_SERVER['DOCUMENT_ROOT'] . $res_file['certification']);
    } else {
        $sql = "UPDATE Public.autoservice_service 
        SET price=" . $pdo->quote($price) .
            ",text=" . $pdo->quote($text) .
            "WHERE autoservice_id=" . $autoservice_id .
            "AND service_id=" . $service_id;
        $res = $pdo->exec($sql);
    }
    service_info($autoservice_id, $service_id);
}
function service_add($autoservice_id, $service_id, $price, $text, $certification = null)
{
    $pdo = conn();
    $price = $pdo->quote($price);
    $text = $pdo->quote($text);
    if ($certification != null) {
        $directory = PATH_UPLOADS_REGULAR . $autoservice_id . '/certification/';
        $name_cert = time() . '_' . $service_id . '-' . translit($_FILES['add_certification']['name']);
        $full_path = $_SERVER['DOCUMENT_ROOT'] . $directory . $name_cert;
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $directory))
            mkdir($_SERVER['DOCUMENT_ROOT'] . $directory, 0777, true);
        if (!move_uploaded_file($_FILES['add_certification']['tmp_name'], $full_path)) {
            $_SESSION['message']['text'] = "Не удалось загрузить файл, попробуйте снова";
            $_SESSION['message']['type'] = 'warning';
            header('Location: /autoservice_service/');
            exit;
        }
        $directory = $pdo->quote($directory . $name_cert);
        $sql = "INSERT INTO Public.autoservice_service(autoservice_id,service_id,price,text,certification)
        VALUES($autoservice_id,$service_id,$price,$text,$directory)";
    } else {
        $sql = "INSERT INTO Public.autoservice_service(autoservice_id,service_id,price,text)
    VALUES($autoservice_id,$service_id,$price,$text)";
    }
    $res = $pdo->exec($sql);
}
function del_serv($autoservice_id, $service_id)
{
    $pdo = conn();
    $sql_get_old_file = "SELECT certification FROM Public.autoservice_service
        WHERE autoservice_id=" . $autoservice_id . "AND service_id=" . $service_id;
    $res_file = $pdo->query($sql_get_old_file)->fetch();
    unlink($_SERVER['DOCUMENT_ROOT'] . $res_file['certification']);
    $sql = "DELETE FROM Public.autoservice_service
    WHERE autoservice_id=" . $autoservice_id . "AND service_id=" . $service_id;
    $res = $pdo->exec($sql);
}

if (!empty($_POST['category_id'])) {
    get_service($_POST['category_id']);
}
if (!empty($_POST['autoserv_category_id'])) {
    get_autoserv_service($_SESSION['user']['id'], $_POST['autoserv_category_id']);
}
if (!empty($_POST['price']) and !empty($_POST['service_id'])) {
    if (!empty($_FILES['certification']))
        service_update($_SESSION['user']['id'], $_POST['service_id'], $_POST['price'], $_POST['text'], $_FILES['certification']);
    else
        service_update($_SESSION['user']['id'], $_POST['service_id'], $_POST['price'], $_POST['text']);
}
if (!empty($_POST['service_id']) and empty($_POST['price'])) {
    service_info($_SESSION['user']['id'], $_POST['service_id']);
}
if (!empty($_POST['add_price']) and !empty($_POST['add_service_id'])) {
    if (!empty($_FILES['add_certification']))
        service_add($_SESSION['user']['id'], $_POST['add_service_id'], $_POST['add_price'], $_POST['add_text'], $_FILES['add_certification']);
    else
        service_add($_SESSION['user']['id'], $_POST['add_service_id'], $_POST['add_price'], $_POST['add_text']);
}

if (!empty($_POST['del_service_id'])) {
    del_serv($_SESSION['user']['id'], $_POST['del_service_id']);
}