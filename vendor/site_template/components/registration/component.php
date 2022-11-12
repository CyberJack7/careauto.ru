<?php
require_once PATH_CONNECT;
$pdo = conn();

$arResult = [];

$sql = "SELECT city_id, name_city FROM Public.city ORDER BY name_city asc";
$city = $pdo->query($sql);
while ($res_city = $city->fetch()) {
    $arResult['CITIES'][$res_city["city_id"]] = [
        'ID' => $res_city["city_id"],
        'NAME' => $res_city["name_city"]
    ];
}