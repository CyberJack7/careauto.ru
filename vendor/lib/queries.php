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


function appl_list($user_id, $status)
{
  $pdo = conn();
  $sql_status = $pdo->quote($status);
  $sql = "SELECT application_id FROM Public.application WHERE autoservice_id=" . $user_id . " AND status = " . $sql_status;
  $appl = $pdo->query($sql);
  if (empty($appl)) {
    echo '<p><div class="alert alert-primary" role="alert">Заявок нет!</div></p>';
  } else {
    $count = 0;
    while ($row = $appl->fetch()) {
      $count++;
      $sql_auto = "SELECT name_brand, name_model FROM Public.application
          JOIN automobile USING(auto_id)
          JOIN brand USING(brand_id) 
          JOIN model USING(model_id)
          WHERE application_id = " . $row['application_id'];
      $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
      $sql_client = "SELECT name_client,phone_client FROM Public.client JOIN Public.application USING(client_id)
      WHERE application_id = " . $row['application_id'];
      $client = $pdo->query($sql_client)->fetch(); // ФИО и телефон клиента

      $sql_serv = "SELECT autoserv_serv_id FROM Public.application WHERE application_id = " . $row['application_id'];
      $serv = $pdo->query($sql_serv)->fetch(); // Извлекаеаем массив услуг из заявки
      $len = strlen($serv['autoserv_serv_id']);
      $str = substr($serv['autoserv_serv_id'], 1, $len - 2);
      $serv_id = explode(',', $str);
      $serv_name = array();
      foreach ($serv_id as $row_serv) {
        $sql_serv_name = "SELECT name_service FROM Public.service WHERE service_id = " . $row_serv;
        array_push($serv_name, $pdo->query($sql_serv_name)->fetch()['name_service']);
      }
      $sql_appl = "SELECT date,text,price FROM Public.application WHERE application_id = " . $row['application_id'];
      $appl_info = $pdo->query($sql_appl)->fetch(); // Дата, комментарий, цена
      echo '<div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-heading' . $count . '">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $count . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $count . '">' .
        $count . '. ' . $client['name_client'] . ' ' . $auto['name_brand'] . ' ' . $auto['name_model'] .
        '</button>
                    </h2>
                    <div id="panelsStayOpen-collapse' . $count . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $count . '">
                        <div class="accordion-body">' .
        'ФИО Клиента: ' . $client['name_client'] . '</br> ' .
        'Телефон Клиента: ' . $client['phone_client'] . '</br> ' .
        'Марка машины: ' . $auto['name_brand'] . '</br> ' .
        'Модель машины: ' . $auto['name_model'] . '</br> ' .
        'Список услуг: </br>';
      $serv_count = 0;
      foreach ($serv_name as $row_serv) {
        $serv_count++;
        echo $serv_count . ' ' . $row_serv . '</br>';
      }
      echo  'Стоимость услуг: ' . $appl_info['price'] . '</br> 
            Дата заявки: ' . $appl_info['date'] . '</br> 
            Комментарий к заявке: ' . $appl_info['text'] . '</br>';
      switch ($status) {
        case "Ожидает подтверждения":
          $button_name = "Подтвердить";
          break;
        case "Подтверждено":
          $button_name = "Начать работу";
          break;
        case "В работе":
          $button_name = "Выполнено";
          break;
        case "Выполнено":
          $button_name = "Завершить";
          break;
      }
      if ($status == "Ожидает подтверждения") {
        echo '<div class="con1"><form action="/vendor/site_template/components/autoservice_applications/component.php" method="post">
            <input name="status" type="hidden" value="Отказ"</input>
            <input name="appl_id" type="hidden" value="' . $row['application_id'] . '"</input>
            <button class="btn btn-secondary" type="submit" >Отклонить заявку</button>      
            </form></div>';
      }
      echo '<div class="con1"> <form action="/vendor/site_template/components/autoservice_applications/component.php" method="post">
      <input name="status" type="hidden" value="' . $status . '"</input>
      <input name="appl_id" type="hidden" value="' . $row['application_id'] . '"</input>
      <button class="btn btn-primary" type="submit" >' . $button_name . '</button>      
      </form></div>';

      echo '</div></div></div>';
    }
  }
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

//полная информация о пользователе
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

//массив расположения фотографий автосервиса
function get_ar_photos($autoservice_id) {
  $pdo = conn();
  $sql = "SELECT photos FROM public.autoservice WHERE autoservice_id = " . $autoservice_id;
  $str_photos = $pdo->query($sql)->fetch()['photos'];
  if ($str_photos != null) {
    $photos = str_replace(['{', '}', "'"], '', $str_photos); //готовим строку к превращению в массив
    $ar_photos = explode(',', $photos); //превращаем в массив
    return $ar_photos;
  } else {
    return null;
  }
}

//массив названий фотографий автосервиса
function get_ar_name_photos($autoservice_id) {
  $ar_photos = get_ar_photos($autoservice_id);
  if (!empty($ar_photos)) {
    $ar_name_photos = [];
    foreach ($ar_photos as $photo) {
      $name_photo = substr($photo, stripos($photo, '-')+1);
      array_push($ar_name_photos, $name_photo);
    }
    return $ar_name_photos;
  }
  return null;
}

//обслуживаемые автосервисом марки авто
function get_autoservice_brands($autoservice_id) {
  $pdo = conn();
  $sql = "SELECT brand_id, name_brand FROM brand JOIN autoservice_brand USING(brand_id) WHERE autoservice_id = " . $autoservice_id;
  $brands = $pdo->query($sql);
  $arResult = [];
  if (!empty($brands)) {     
    while ($brand = $brands->fetch()) { //для каждого авто
      array_push($arResult, ['id' => $brand['brand_id'], 'name' => $brand['name_brand']]);
    }
  }
  if (!empty($arResult)) {
    return $arResult;
  } else {
    return null;
  }
}

//все марки авто
function brands() {
  $pdo = conn();
  $sql = "SELECT * FROM public.brand";
  $brands = $pdo->query($sql);
  $arResult = [];
  if (!empty($brands)) {     
    while ($brand = $brands->fetch()) { //для каждого авто
      array_push($arResult, ['id' => $brand['brand_id'], 'name' => $brand['name_brand']]);
    }
  }
  if (!empty($arResult)) {
    return $arResult;
  } else {
    return null;
  }
}

//модели авто по id марки
function modelById($brand_id) {
  $pdo = conn();
  $sql = "SELECT model_id, name_model FROM public.model WHERE brand_id = " . $brand_id;
  $models = $pdo->query($sql);
  $arResult = [];
  if (!empty($models)) {     
    while ($model = $models->fetch()) { //для каждой модели
      array_push($arResult, ['id' => $model['model_id'], 'name' => $model['name_model']]);
    }
  }
  if (!empty($arResult)) {
    return $arResult;
  } else {
    return null;
  }
}