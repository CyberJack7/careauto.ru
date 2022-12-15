<?php
session_start();

//на страницу сохранения инфы может переадресовываться только авторизованный пользователь
if ($_SESSION['user']['user_type'] != 'autoservice') {
    header('Location: /');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;
$pdo = conn();

$inn = htmlspecialchars($_POST['inn']);
$bik = htmlspecialchars($_POST['bik']);
$check_acc = htmlspecialchars($_POST['check_acc']);
if (!empty($_POST['kpp'])) {
    $kpp = htmlspecialchars($_POST['kpp']);
} else {
    $kpp = null;
}
if (!empty($_POST['corr_acc'])) {
    $corr_acc = htmlspecialchars($_POST['corr_acc']);
} else {
    $corr_acc = null;
}

$requisites_id = getRequisitesId($_SESSION['user']['id']);
if ($requisites_id == null) { //если реквизиты ещё не добавлялись
    $sql_insert_requisites = "INSERT INTO public.requisites(inn, kpp, bik, check_acc, corr_acc) VALUES (:inn, :kpp, :bik, :check_acc, :corr_acc)";
    $stmt = $pdo->prepare($sql_insert_requisites);
    $stmt->execute([
        'inn' => $inn,
        'kpp' => $kpp,
        'bik' => $bik,
        'check_acc' => $check_acc,
        'corr_acc' => $corr_acc
    ]);
    
    $requisites_id = $pdo->lastInsertId();
    
    $sql_insert_requisites = "UPDATE public.autoservice SET requisites_id = " . $requisites_id . " WHERE autoservice_id = " . $_SESSION['user']['id'];
    $stmt = $pdo->exec($sql_insert_requisites);
    $_SESSION['message']['text'] = 'Реквизиты изменены успешно!';
    $_SESSION['message']['type'] = 'success';
} else { //при необходимости изменения уже существующих
    $sql_update_requisites = "UPDATE public.requisites SET inn = :inn, kpp = :kpp, bik = :bik, check_acc = :check_acc, corr_acc = :corr_acc WHERE requisites_id = " . $requisites_id;
    $stmt = $pdo->prepare($sql_update_requisites);
    $stmt->execute([
        'inn' => $inn,
        'kpp' => $kpp,
        'bik' => $bik,
        'check_acc' => $check_acc,
        'corr_acc' => $corr_acc
    ]);
}
header('Location: /profile/');
exit;
