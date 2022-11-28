<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//на странице добавления автомобилей автовладельца может находиться только автовладелец
if (!($_SESSION['user']['user_type'] == 'client')) {
    header('Location: /');
}


//добавление автомобиля
$auto['brand_id'] = htmlspecialchars($_POST['brand']);
$auto['model_id'] = htmlspecialchars($_POST['model']);
$auto['configuration'] = htmlspecialchars($_POST['configuration']);
$auto['auto_year'] = htmlspecialchars($_POST['auto_year']);
$auto['date_buy'] = htmlspecialchars($_POST['date_buy']);
$auto['mileage'] = htmlspecialchars($_POST['mileage']);
$auto['color'] = htmlspecialchars($_POST['color']);
$auto['engine_volume'] = htmlspecialchars($_POST['engine_volume']);
$auto['engine_power'] = htmlspecialchars($_POST['engine_power']);
$auto['pts'] = htmlspecialchars($_POST['PTS']);
$auto['vin'] = htmlspecialchars($_POST['VIN']);
if (isset($_POST['body'])) {
    $auto['body_id'] = htmlspecialchars($_POST['body']);
} else {
    $auto['body_id'] = NULL;
}
if (isset($_POST['engine'])) {
    $auto['engine_id'] = htmlspecialchars($_POST['engine']);
} else {
    $auto['engine_id'] = NULL;
}
if (isset($_POST['gearbox'])) {
    $auto['gearbox_id'] = htmlspecialchars($_POST['gearbox']);
} else {
    $auto['gearbox_id'] = NULL;
}
if (isset($_POST['drive'])) {
    $auto['drive_id'] = htmlspecialchars($_POST['drive']);
} else {
    $auto['drive_id'] = NULL;
}
foreach ($auto as &$character) {
    if($character == "") {
        $character = NULL;
    }
}

$sql_create_auto = "INSERT INTO public.automobile(client_id, vin, brand_id, model_id, configuration, date_buy, mileage, body_id, color, 
engine_id, engine_volume, engine_power, gearbox_id, drive_id, pts, auto_year, tires_id) VALUES (:client_id, :vin, :brand_id, :model_id, 
:configuration, :date_buy, :mileage, :body_id, :color, :engine_id, :engine_volume, :engine_power, :gearbox_id, :drive_id, :pts, :auto_year, :tires_id)";
$stmt = $pdo->prepare($sql_create_auto);
$stmt->execute([
    'client_id' => $_SESSION['user']['id'],
    'vin' => $auto['vin'],
    'brand_id' => $auto['brand_id'],
    'model_id' => $auto['model_id'],
    'configuration' => $auto['configuration'],
    'date_buy' => $auto['date_buy'],
    'mileage' => $auto['mileage'],
    'body_id' => $auto['body_id'],
    'color' => $auto['color'],
    'engine_id' => $auto['engine_id'],
    'engine_volume' => $auto['engine_volume'],
    'engine_power' => $auto['engine_power'],
    'gearbox_id' => $auto['gearbox_id'],
    'drive_id' => $auto['drive_id'],
    'pts' => $auto['pts'],
    'auto_year' => $auto['auto_year'],
    'tires_id' => NULL
]);
$_SESSION['message']['text'] = 'Добавлен новый автомобиль!';
$_SESSION['message']['type'] = 'success';

header('Location: /my_auto/');
exit;