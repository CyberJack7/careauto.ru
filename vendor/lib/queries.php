<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;

//список автомобилей автовладельца (+ количество)
function cars_list($user_id) {
    $pdo = conn();
    $sql = "SELECT auto_id FROM Public.automobile WHERE client_id = " . $user_id;
    $cars = $pdo->query($sql); //список авто по id
    $arResult = [];
    if (!empty($cars)) {     
      while ($row = $cars->fetch()) { //для каждого авто
          $sql_auto = "SELECT name_brand, name_model FROM automobile
          JOIN brand USING(brand_id) JOIN model USING(model_id) 
          WHERE auto_id = " . $row['auto_id'];
          $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
          array_push($arResult, ['brand' => $auto['name_brand'], 'model' => $auto['name_model']]);
      }
    }
    return $arResult;
}


//список городов
function city_list(){
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
  return $arResult;
}

//название города по id
function city_id_name($city_id){
  $pdo = conn();
  $sql = "SELECT name_city FROM Public.city WHERE city_id = " . $city_id;
  $city_name = $pdo->query($sql)->fetch()['name_city'];
  return $city_name;
}