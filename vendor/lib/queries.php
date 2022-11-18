<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;

//список автомобилей автовладельца (+ количество)
function cars_list($user_id) {
    $pdo = conn();
    $sql = "SELECT auto_id FROM public.automobile WHERE client_id = " . $user_id;
    $cars = $pdo->query($sql); //список авто по id
    $arResult = [];
    if (!empty($cars)) {     
      while ($row = $cars->fetch()) { //для каждого авто
          $sql_auto = "SELECT name_brand, name_model FROM public.automobile
          JOIN public.brand USING(brand_id) JOIN public.model USING(model_id) 
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
  $sql = "SELECT city_id, name_city FROM public.city ORDER BY name_city asc";
  $city = $pdo->query($sql);
  while ($res_city = $city->fetch()) {
      $arResult['CITIES'][$res_city["city_id"]] = [
          'ID' => $res_city["city_id"],
          'NAME' => $res_city["name_city"]
      ];
  }
  return $arResult;
}

//название города по city_id
function city_id_name($city_id){
  $pdo = conn();
  $sql = "SELECT name_city FROM public.city WHERE city_id = " . $city_id;
  $city_name = $pdo->query($sql)->fetch()['name_city'];
  return $city_name;
}

//адрес по autoservice_id
function address_name($autoservice_id){
  $pdo = conn();
  $sql = "SELECT address FROM public.autoservice WHERE autoservice_id = " . $autoservice_id;
  $address = $pdo->query($sql)->fetch()['address'];
  return $address;
}

//путь к файлу по user_id
function doc_path($user_id){
  $pdo = conn();
  $sql = "SELECT document FROM public.autoservice WHERE autoservice_id = " . $user_id;
  $doc_name = $pdo->query($sql)->fetch()['document'];
  return $doc_name;
}

//requisites_id по autoservice_id
function requisites_id($autoservice_id) {
  $pdo = conn();
  $sql_find_requisites_id = "SELECT requisites_id FROM public.autoservice WHERE autoservice_id = " . $autoservice_id;
  $requisites_id = $pdo->query($sql_find_requisites_id)->fetch()['requisites_id'];
  return $requisites_id;
}

//реквизиты по autoservice_id
function requisites($autoservice_id){
  $pdo = conn();
  $requisites_id = requisites_id($autoservice_id);

  if ($requisites_id != null) {
    $sql_find_requisites = "SELECT * FROM public.requisites WHERE requisites_id = '" . $requisites_id . "'";
    $requisites = $pdo->query($sql_find_requisites)->fetch();
    return $requisites;
  } else {
    return null;
  }
}

function get_all_userinfo($user_id, $user_type=null) {
  $pdo = conn();
  $sql_array_check = [
    "check_admin_sql" => "SELECT * FROM public.admin WHERE admin_id = " . $user_id,
    "check_autoservice_sql" => "SELECT * FROM public.autoservice WHERE autoservice_id = " . $user_id,
    "check_client_sql" => "SELECT * FROM public.client WHERE client_id = " . $user_id
  ];

  if ($user_type == 'admin') {
    $check_user = $pdo->query($sql_array_check['check_admin_sql'])->fetch();
    if (!empty($check_user)) {
      return $check_user;
    }
    return null;
  }
  if ($user_type == 'autoservice') {
    $check_user = $pdo->query($sql_array_check['check_autoservice_sql'])->fetch();
    if (!empty($check_user)) {
      return $check_user;
    }
    return null;
  }
  if ($user_type == 'client') {
    $check_user = $pdo->query($sql_array_check['check_client_sql'])->fetch();
    if (!empty($check_user)) {
      return $check_user;
    }
    return null;
  }

  foreach ($sql_array_check as $sql) {
      $check_user = $pdo->query($sql)->fetch();
      if (!empty($check_user)) {
          return $check_user;
      } 
  }
  return null;
}