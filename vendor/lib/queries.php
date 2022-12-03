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
      $sql_auto = "SELECT auto_id, name_brand, name_model FROM public.automobile
        JOIN public.brand USING(brand_id) JOIN public.model USING(model_id) 
        WHERE auto_id = " . $row['auto_id'];
      $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
      array_push($arResult, ['id' => $auto['auto_id'], 'brand' => $auto['name_brand'], 'model' => $auto['name_model']]);
    }
    return $arResult;
  }
  return null;
}

// вывод заявок с определенным статусом
function appl_list($user_id, $status)
{
  $pdo = conn();
  $sql_status = $pdo->quote($status);
  $sql = "SELECT application_id FROM Public.application WHERE autoservice_id=" . $user_id . " AND status = " . $sql_status;
  $appl = $pdo->query($sql);
  if ($appl->rowCount() == 0) {
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
      $str = str_replace(['{', '}', ' '], '', $serv['autoserv_serv_id']);
      $serv_id = explode(',', $str);
      $serv_name = array();
      foreach ($serv_id as $row_serv) {
        $sql_serv_name = "SELECT name_service FROM Public.service WHERE service_id = " . $row_serv;
        array_push($serv_name, $pdo->query($sql_serv_name)->fetch()['name_service']);
      }
      $sql_appl = "SELECT date,text,price FROM Public.application WHERE application_id = " . $row['application_id'];
      $appl_info = $pdo->query($sql_appl)->fetch(); // Дата, комментарий, цена
      $space_ind = strpos($appl_info['date'], ' ');
      $date = mb_substr($appl_info['date'], 0, $space_ind);
      $time = mb_substr($appl_info['date'], $space_ind + 1);

      echo '<div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-heading' . $count . '">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $count . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $count . '">' .
        $count . '. ' . $auto['name_brand'] . ' ' . $auto['name_model'] . ' ' . $client['name_client'] . ' ' . $client['phone_client'] .
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
            Комментарий к заявке: ' . $appl_info['text'] . '</br>';
      if ($status == "Ожидает подтверждения") {
        echo 'Дата заявки: <input name="date" type="date" value="' . $date . '"</input></br>
        Время заявки: <input name="time" type="datetime" value="' . $time . '"</input></br>';
      } else {
        echo 'Дата заявки: ' . $date . '</br>
              Время заявки: ' . $time . '</br>';
      }

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
      if ($status == "Ожидает подтверждения" or $status == "Подтверждено") {
        echo '<div class="con1">
            <button id="cancel_btn" class="btn btn-secondary" type="button" >Отклонить заявку</button>      
            </div>';
      }
      echo '<div class="con1">
      <input name="status" type="hidden" value="' . $status . '"</input>
      <input name="appl_id" type="hidden" value="' . $row['application_id'] . '"</input>
      <button id="accept_btn" class="btn btn-primary" type="button" >' . $button_name . '</button>      
      </div>';

      echo '</div></div></div>';
    }
  }
}


//список городов
function city_list()
{
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
function getCityNameById($city_id){
  $pdo = conn();
  $sql = "SELECT name_city FROM public.city WHERE city_id = " . $city_id;
  $city_name = $pdo->query($sql)->fetch()['name_city'];
  return $city_name;
}

//адрес по autoservice_id
function address_name($autoservice_id)
{
  $pdo = conn();
  $sql = "SELECT address FROM public.autoservice WHERE autoservice_id = " . $autoservice_id;
  $address = $pdo->query($sql)->fetch()['address'];
  return $address;
}

//путь к файлу по user_id
function doc_path($user_id)
{
  $pdo = conn();
  $sql = "SELECT document FROM public.autoservice WHERE autoservice_id = " . $user_id;
  $doc_name = $pdo->query($sql)->fetch()['document'];
  return $doc_name;
}

//requisites_id по autoservice_id
function requisites_id($autoservice_id)
{
  $pdo = conn();
  $sql_find_requisites_id = "SELECT requisites_id FROM public.autoservice WHERE autoservice_id = " . $autoservice_id;
  $requisites_id = $pdo->query($sql_find_requisites_id)->fetch()['requisites_id'];
  return $requisites_id;
}

//реквизиты по autoservice_id
function requisites($autoservice_id)
{
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
function getAllUserInfo($user_id, $user_type = null)
{
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
function getPhotosArray($autoservice_id)
{
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
function getPhotosNames($autoservice_id)
{
  $ar_photos = getPhotosArray($autoservice_id);
  if (!empty($ar_photos)) {
    $ar_name_photos = [];
    foreach ($ar_photos as $photo) {
      $name_photo = substr($photo, stripos($photo, '-') + 1);
      array_push($ar_name_photos, $name_photo);
    }
    return $ar_name_photos;
  }
  return null;
}

//обслуживаемые автосервисом марки авто
function getAutoserviceBrands($autoservice_id)
{
  $pdo = conn();
  $sql = "SELECT brand_id, name_brand FROM brand JOIN autoservice_brand USING(brand_id) WHERE autoservice_id = " . $autoservice_id  . " ORDER BY name_brand";
  $brands = $pdo->query($sql);
  $arResult = [];
  if (!empty($brands)) {
    while ($brand = $brands->fetch()) { //для каждого авто
      array_push($arResult, ['id' => $brand['brand_id'], 'name' => $brand['name_brand']]);
    }
    return $arResult;
  }
  return null;
}

//модели авто по id марки
function getModelById($brand_id) {
  $pdo = conn();
  $sql = "SELECT model_id, name_model FROM public.model WHERE brand_id = " . $brand_id . " ORDER BY name_model";
  $models = $pdo->query($sql);
  $arResult = [];
  if (!empty($models)) {     
    while ($model = $models->fetch()) { //для каждой модели
      array_push($arResult, ['id' => $model['model_id'], 'name' => $model['name_model']]);
    }
    return $arResult;
  }
  return null;
}

//информация об авто по id авто
function getAutoInfoById($auto_id) {
  $pdo = conn();
  $sql = "SELECT * FROM public.automobile WHERE auto_id = " . $auto_id;
  $auto = $pdo->query($sql)->fetch();
  $arResult = [];
  if ($auto != null) {
    $str_tires_id = str_replace(['{', '}'], '', $auto['tires_id']); //готовим строку к превращению в массив
    $ar_tires_id = explode(',', $str_tires_id); //превращаем в массив
    foreach ($auto as &$item) {
      if ($item == null) {
        $item = "-";
      }
    }
    if ($ar_tires_id == "-") {
      $ar_tires_id = null;
    }
    if ($auto['brand_id'] != '-') {
      $auto['brand_id'] = getBrandNameById($auto['brand_id']);
    }
    if ($auto['model_id'] != '-') {
      $auto['model_id'] = getModelNameById($auto['model_id']);
    }
    if ($auto['body_id'] != '-') {
      $auto['body_id'] = getBodyNameById($auto['body_id']);
    }
    if ($auto['engine_id'] != '-') {
      $auto['engine_id'] = getEngineNameById($auto['engine_id']);
    }
    if ($auto['gearbox_id'] != '-') {
      $auto['gearbox_id'] = getGearboxNameById($auto['gearbox_id']);
    }
    if ($auto['drive_id'] != '-') {
      $auto['drive_id'] = getDriveNameById($auto['drive_id']);
    }
    $arResult = [
      'id' => $auto['auto_id'],
      'client_id' => $auto['client_id'],
      'brand' => $auto['brand_id'],
      'model' => $auto['model_id'],
      'configuration' => $auto['configuration'],
      'auto_year' => $auto['auto_year'],
      'date_buy' => $auto['date_buy'],
      'mileage' => $auto['mileage'],
      'body' => $auto['body_id'],
      'color' => $auto['color'],
      'engine' => $auto['engine_id'],
      'engine_volume' => $auto['engine_volume'],
      'engine_power' => $auto['engine_power'],
      'gearbox' => $auto['gearbox_id'],
      'drive' => $auto['drive_id'],
      'tires_id' => $ar_tires_id,
      'pts' => $auto['pts'],
      'vin' => $auto['vin']
    ];
    return $arResult;
  }
  return null;
}

//Название марки по id марки
function getBrandNameById($brand_id) {
  $pdo = conn();
  $sql = "SELECT name_brand FROM public.brand WHERE brand_id = " . $brand_id;
  $brand = $pdo->query($sql);
  if (!empty($brand)) {
    return $brand->fetch()['name_brand'];
  }
  return null;
}

//Название модели по id модели
function getModelNameById($model_id) {
  $pdo = conn();
  $sql = "SELECT name_model FROM public.model WHERE model_id = " . $model_id;
  $model = $pdo->query($sql);
  if (!empty($model)) {
    return $model->fetch()['name_model'];
  }
  return null;
}

//Название кузова по id кузова
function getBodyNameById($body_id) {
  $pdo = conn();
  $sql = "SELECT name_body FROM public.body WHERE body_id = " . $body_id;
  $body = $pdo->query($sql);
  if (!empty($body)) {
    return $body->fetch()['name_body'];
  }
  return null;
}

//Название типа двигателя по id двигателя
function getEngineNameById($engine_id) {
  $pdo = conn();
  $sql = "SELECT name_engine FROM public.engine WHERE engine_id = " . $engine_id;
  $engine = $pdo->query($sql);
  if (!empty($engine)) {
    return $engine->fetch()['name_engine'];
  }
  return null;
}

//Название типа коробки по id коробки
function getGearboxNameById($gearbox_id) {
  $pdo = conn();
  $sql = "SELECT name_gearbox FROM public.gearbox WHERE gearbox_id = " . $gearbox_id;
  $gearbox = $pdo->query($sql);
  if (!empty($gearbox)) {
    return $gearbox->fetch()['name_gearbox'];
  }
  return null;
}

//Название типа привода по id привода
function getDriveNameById($drive_id) {
  $pdo = conn();
  $sql = "SELECT name_drive FROM public.drive WHERE drive_id = " . $drive_id;
  $drive = $pdo->query($sql);
  if (!empty($drive)) {
    return $drive->fetch()['name_drive'];
  }
  return null;
}

//Список комплектов резины по id автомобиля
function getTiresListById($auto_id) {
  $pdo = conn();
  $sql = "SELECT tires_id FROM public.automobile WHERE auto_id = " . $auto_id;
  $tires = $pdo->query($sql)->fetch()['tires_id'];
  if ($tires != null) {
    $str_tires = str_replace(['{', '}'], '', $tires); //готовим строку к превращению в массив
    $ar_tires = explode(',', $str_tires); //превращаем в массив
    return $ar_tires;
  } else {
    return null;
  }
}

//Полная информация о комплекте резины id комплекта
function getTiresInfoById($tires_id) {
  $pdo = conn();
  $sql = "SELECT * FROM public.tires WHERE tires_id = " . $tires_id;
  $tires = $pdo->query($sql)->fetch();
  if ($tires != null) {
    if ($tires['marking'] == null) {
        $tires['marking'] = "-";
    }
    if ($tires['date_buy'] == null) {
        $tires['date_buy'] = "-";
    }
    $arResult = [
      'tires_id' => $tires['tires_id'],
      'brand_tires' => $tires['brand_tires'],
      'tire_type' => getTiresTypeNameById($tires['tire_type_id']),
      'marking' => $tires['marking'],
      'date_buy' => $tires['date_buy']
    ];
    return $arResult;
  }
  return null;
}

//Название типа резины по id типа
function getTiresTypeNameById($tire_type_id) {
  $pdo = conn();
  $sql = "SELECT name_tire_type FROM public.tire_type WHERE tire_type_id = " . $tire_type_id;
  $tire_type = $pdo->query($sql);
  if (!empty($tire_type)) {
    return $tire_type->fetch()['name_tire_type'];
  }
  return null;
}


//Список автомобилей по id автовладельца
function getAutosById($client_id) {
  $pdo = conn();
  $sql = "SELECT auto_id, brand_id, model_id FROM public.automobile WHERE client_id = " . $client_id;
  $autos = $pdo->query($sql);
  $arResult = [];
  while ($auto = $autos->fetch()) {
    $arResult[$auto['auto_id']] = ['brand' => getBrandNameById($auto['brand_id']), 'model' => getModelNameById($auto['model_id'])];
  }
  if (!empty($arResult)) {
    return $arResult;
  }
  return null;
}


//все марки авто
function brands()
{
  $pdo = conn();
  $sql = "SELECT * FROM public.brand ORDER BY name_brand";
  $brands = $pdo->query($sql);
  $arResult = [];
  if (!empty($brands)) {
    while ($brand = $brands->fetch()) { //для каждого авто
      array_push($arResult, ['id' => $brand['brand_id'], 'name' => $brand['name_brand']]);
    }
    return $arResult;
  }
  return null;
}

//все типы кузова
function bodies() {
  $pdo = conn();
  $sql = "SELECT * FROM public.body ORDER BY name_body";
  $bodies = $pdo->query($sql);
  $arResult = [];
  if (!empty($bodies)) {     
    while ($body = $bodies->fetch()) { //для каждого кузова
      array_push($arResult, ['id' => $body['body_id'], 'name' => $body['name_body']]);
    }
    return $arResult;
  }
  return null;
}

//все типы двигателя
function engines() {
  $pdo = conn();
  $sql = "SELECT * FROM public.engine ORDER BY name_engine";
  $engines = $pdo->query($sql);
  $arResult = [];
  if (!empty($engines)) {     
    while ($engine = $engines->fetch()) { //для каждого типа двигателя
      array_push($arResult, ['id' => $engine['engine_id'], 'name' => $engine['name_engine']]);
    }
    return $arResult;
  }
  return null;
}

//все типы КПП
function gearboxes() {
  $pdo = conn();
  $sql = "SELECT * FROM public.gearbox ORDER BY name_gearbox";
  $gearboxes = $pdo->query($sql);
  $arResult = [];
  if (!empty($gearboxes)) {
    while ($gearbox = $gearboxes->fetch()) { //для каждого типа КПП
      array_push($arResult, ['id' => $gearbox['gearbox_id'], 'name' => $gearbox['name_gearbox']]);
    }
    return $arResult;
  }
  return null;
}

//все типы приводов
function drives() {
  $pdo = conn();
  $sql = "SELECT * FROM public.drive ORDER BY name_drive";
  $drives = $pdo->query($sql);
  $arResult = [];
  if (!empty($drives)) {     
    while ($drive = $drives->fetch()) { //для каждого привода
      array_push($arResult, ['id' => $drive['drive_id'], 'name' => $drive['name_drive']]);
    }
    return $arResult;
  }
  return null;
}

//все типы резины
function tires() {
  $pdo = conn();
  $sql = "SELECT * FROM public.tire_type ORDER BY name_tire_type";
  $tire_types = $pdo->query($sql);
  $arResult = [];
  if (!empty($tire_types)) {     
    while ($tire_type = $tire_types->fetch()) { //для каждого типа
      array_push($arResult, ['id' => $tire_type['tire_type_id'], 'name' => $tire_type['name_tire_type']]);
    }
    return $arResult;
  }
  return null;
}


//Список всех категорий услуг
function get_category_list()
{
  $pdo = conn();
  $sql_category = "SELECT name_category,serv_category_id FROM Public.serv_category
  ORDER BY name_category ASC"; // Извлекаем список наименований категорий и их ID
  $category = $pdo->query($sql_category);
  $arCategory = [];
  while ($row_category = $category->fetch()) {
    $arCategory[$row_category['serv_category_id']] = $row_category['name_category'];
  }
  return $arCategory;
}


//Список услуг по id категории
function getServicesById($category_id)
{
  $pdo = conn();
  $sql = "SELECT service_id, name_service FROM Public.service WHERE serv_category_id = " . $category_id . " ORDER BY name_service ASC";
  $services = $pdo->query($sql);
  $arServices = [];
  if (!empty($services)) {
    while ($service = $services->fetch()) {
      array_push($arServices, ['id' => $service['service_id'], 'name' => $service['name_service']]);
    }
    return $arServices;
  }
  return NULL;
}


// Вывод заявок на регистрацию СЦ для админа
function admin_appl_list()
{
  $pdo = conn();
  $sql = "SELECT autoservice_temp_id FROM Public.autoservice_in_check";
  $appl = $pdo->query($sql);
  if ($appl->rowCount() == 0) {
    echo '<p><div class="alert alert-primary" role="alert">Заявок нет!</div></p>';
  } else {
    $count = 0;
    while ($row = $appl->fetch()) {
      $count++;
      $sql_info = "SELECT name_autoservice,email_autoservice,phone_autoservice,document,city_id FROM Public.autoservice_in_check 
      WHERE autoservice_temp_id=" . $row['autoservice_temp_id'];
      $autoserv_info = $pdo->query($sql_info)->fetch();
      $sql_city = "SELECT name_city FROM Public.city WHERE city_id=" . $autoserv_info['city_id'];
      $city = $pdo->query($sql_city)->fetch();
      echo '<div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-heading' . $count . '">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $count . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $count . '">' .
        $count . '. ' . $autoserv_info['name_autoservice'] . ' ' . $autoserv_info['email_autoservice'] .
        '</button>
                    </h2>
                    <div id="panelsStayOpen-collapse' . $count . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $count . '">
                        <div class="accordion-body">' .
        'Название СЦ: ' . $autoserv_info['name_autoservice'] . '</br> ' .
        'Почта СЦ: ' . $autoserv_info['email_autoservice'] . '</br> ' .
        'Телефон СЦ: ' . $autoserv_info['phone_autoservice'] . '</br> ' .
        'Город: ' . $city['name_city'] . '</br> ' .
        'Документ: ' . '<a href="' . $autoserv_info['document'] . '" target="_blank">Ссылка на документ</a>' . '</br> ';

      echo '<div class="con1">
        <input name="autoserv_temp_id" type="hidden" value="' . $row['autoservice_temp_id'] . '"</input>
        <input name="document" type="hidden" value="' . $autoserv_info['document'] . '"</input>
        <input name="email" type="hidden" value="' . $autoserv_info['email_autoservice'] . '"</input>
        <button id="cancel_btn" class="btn btn-secondary" type="button" >Отклонить заявку</button>
        <button id="accept_btn" class="btn btn-primary" type="button" >Зарегистрировать СЦ</button>      
        </div>';
      echo '</div></div></div>';
    }
  }
}


// Список категорий автосервиса
function getAutoserviceCategoryList($autoservice_id) 
{
  $pdo = conn();
  $arService = getAutoserviceServiceList($autoservice_id);
  $arCategory = [];
  foreach ($arService as $key => $value) { // $key - ID услуги
    $sql = "SELECT serv_category_id FROM Public.service
    WHERE service_id=" . $key;
    $category_id = $pdo->query($sql)->fetch();
    $sql_category_name = "SELECT name_category FROM Public.serv_category
    WHERE serv_category_id=" . $category_id['serv_category_id'];
    $category_name = $pdo->query($sql_category_name)->fetch();
    $arCategory[$category_id['serv_category_id']] = $category_name['name_category'];
  }
  return $arCategory;
}


// Список услуг автосервиса
function getAutoserviceServiceList($autoservice_id, $category = 'None') 
{
  $pdo = conn();
  if ($category != 'None') {
    $sql = "SELECT service_id FROM Public.autoservice_service
    JOIN Public.service USING(service_id) WHERE autoservice_id=" . $autoservice_id .
      "AND serv_category_id=" . $category;
  } else {
    $sql = "SELECT service_id FROM Public.autoservice_service
    WHERE autoservice_id=" . $autoservice_id;
  }
  $autoserv_services = $pdo->query($sql); // Извлекаем список услуг из СЦ
  $arService = [];
  if (!empty($autoserv_services)) {
    while ($row = $autoserv_services->fetch()) {
      $sql_name = "SELECT name_service 
      FROM Public.service
      WHERE service_id=" . $row['service_id'];
      $service = $pdo->query($sql_name)->fetch();
      $arService[$row['service_id']] = $service['name_service'];
    }
    return $arService;
  }
}


// Информация о конкретной услуге автосервиса
function getServiceInfo($autoservice_id, $service_id) 
{
  $pdo = conn();
  $sql = "SELECT price,text,certification FROM Public.autoservice_service
  WHERE autoservice_id = " . $autoservice_id . " AND service_id = " . $service_id;
  $arService = $pdo->query($sql)->fetch();
  $arResult = [];
  if (!empty($arService)) {
    $arResult['category'] = getCategoryNameById($service_id);
    $arResult['name'] = getServiceNameById($service_id);
    $arResult['price'] = $arService['price'];
    $arResult['text'] = $arService['text'];
    $arResult['certification'] = $arService['certification'];
    return $arResult;
  }
  return NULL;
}


// Название услуги по её id
function getServiceNameById($service_id) {
  $pdo = conn();
  $sql = "SELECT name_service FROM Public.service WHERE service_id = " . $service_id;
  $service_name = $pdo->query($sql)->fetch()['name_service'];
  return $service_name;
}


// Название категории по id услуги
function getCategoryNameById($service_id) {
  $pdo = conn();
  $sql = "SELECT name_category FROM Public.serv_category JOIN Public.service USING(serv_category_id) WHERE service_id = " . $service_id;
  $category_name = $pdo->query($sql)->fetch()['name_category'];
  return $category_name;
}


//Список услуг, которые ещё не были добавлены автосервисом в свой перечень услуг
function getServiceList($category_id, $autoservice_id)
{
  $pdo = conn();
  $sql_service = "SELECT name_service,service_id FROM Public.service 
  WHERE serv_category_id=" . $category_id . "AND service_id NOT IN (SELECT service_id FROM Public.autoservice_service WHERE autoservice_id=" . $autoservice_id . ") ORDER BY name_service ASC";
  $service = $pdo->query($sql_service);
  $arService = [];
  while ($row_service = $service->fetch()) {
    $arService[$row_service['service_id']] = $row_service['name_service'];
  }
  return $arService;
}


//Список id категорий автосервиса
function getCategoriesIdList($autoservice_id) {
  $pdo = conn();
  $sql = "SELECT DISTINCT serv_category_id FROM public.autoservice_service JOIN public.service USING(service_id) 
    WHERE autoservice_id = " . $autoservice_id;
  $categories = $pdo->query($sql);
  $arCategories = [];
  while ($category = $categories->fetch()) {
    array_push($arCategories, $category['serv_category_id']);
  }
  return $arCategories;
}


//Список id услуг автосервиса
function getServicesIdList($autoservice_id) {
  $pdo = conn();
  $sql = "SELECT service_id FROM Public.autoservice_service WHERE autoservice_id = " . $autoservice_id; 
  $services = $pdo->query($sql);
  $arService = [];
  while ($service = $services->fetch()) {
    array_push($arService, $service['service_id']);
  }
  return $arService;
}


//Список сервисных центров, удовлетворяющих параметрам фильтрации
function getAutoservicesByParameters($parametres = NULL) {
  $pdo = conn();
  $sql_autoserv = "SELECT DISTINCT autoservice_id, name_autoservice FROM public.autoservice_brand JOIN public.autoservice 
    USING(autoservice_id) JOIN public.autoservice_service USING(autoservice_id) JOIN public.service USING(service_id)    ";
  if ($parametres != NULL) {
    if (isset($parametres['name']) && $parametres['name'] != NULL) {
      if(mb_strpos($sql_autoserv, "WHERE") === false) {
        $sql_autoserv .= "WHERE ";
      }
      $sql_autoserv .= "name_autoservice = " . $pdo->quote($parametres['name']) . ' AND ';
    }
    if (isset($parametres['city']) && $parametres['city'] != NULL) {
      if(mb_strpos($sql_autoserv, "WHERE") === false) {
        $sql_autoserv .= "WHERE ";
      }
      $sql_autoserv .= "city_id = " . $parametres['city'] . ' AND ';
    }    
    if (isset($parametres['auto_id']) && $parametres['auto_id'] != NULL) {
      if(mb_strpos($sql_autoserv, "WHERE") === false) {
        $sql_autoserv .= "WHERE ";
      }
      $sql_brand_id = "SELECT brand_id FROM public.automobile WHERE auto_id = " . $parametres['auto_id'];
      $brand_id = $pdo->query($sql_brand_id)->fetch()['brand_id'];
      $sql_autoserv .= "brand_id = " . $brand_id . ' AND ';
    }
    if (isset($parametres['categories']) && $parametres['categories'] != NULL) {
      if(mb_strpos($sql_autoserv, "WHERE") === false) {
        $sql_autoserv .= "WHERE ";
      }
      $str_categories = '(' . implode(',', $parametres['categories']) . ')';
      $sql_autoserv .= "serv_category_id IN " . $str_categories . ' AND ';
    }
    if (isset($parametres['services']) && $parametres['services'] != NULL) {
      if(mb_strpos($sql_autoserv, "WHERE") === false) {
        $sql_autoserv .= "WHERE ";
      }
      $str_services = '(' . implode(',', $parametres['services']) . ')';
      $sql_autoserv .= "service_id IN " . $str_services . ' AND ';
    }
  }
  $sql_autoserv = substr($sql_autoserv, 0, strlen($sql_autoserv) - 4);
  $sql_autoserv .= " ORDER BY name_autoservice ASC";
  $autoservices = $pdo->query($sql_autoserv);
  $full_accord = [];
  $partly_accord = [];
  if (isset($parametres['categories']) && $parametres['categories'] != NULL) {
    if (isset($parametres['services']) && $parametres['services'] != NULL) {
      while ($autoservice = $autoservices->fetch()) {
        $autoserv_categories = getCategoriesIdList($autoservice['autoservice_id']);
        $autoserv_service = getServicesIdList($autoservice['autoservice_id']);
        if (empty(array_diff($parametres['categories'], $autoserv_categories)) && empty(array_diff($parametres['services'], $autoserv_service))) {
          $full_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
        } else {
          $partly_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
        }
      }
    } else {
      while ($autoservice = $autoservices->fetch()) {
        $autoserv_categories = getCategoriesIdList($autoservice['autoservice_id']);
        if (empty(array_diff($parametres['categories'], $autoserv_categories))) {
          $full_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
        } else {
          $partly_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
        }
      }
    }
  } elseif (isset($parametres['services']) && $parametres['services'] != NULL) {
    while ($autoservice = $autoservices->fetch()) {
      $autoserv_service = getServicesIdList($autoservice['autoservice_id']);
      if (empty(array_diff($parametres['services'], $autoserv_service))) {
        $full_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
      } else {
        $partly_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
      }
    }
  } else {
    while ($autoservice = $autoservices->fetch()) {
      $full_accord[$autoservice['autoservice_id']] = $autoservice['name_autoservice'];
    }
  }
  asort($full_accord);
  asort($partly_accord);
  $ar_sort = $full_accord + $partly_accord;
  $arResult = [];
  foreach ($ar_sort as $id => $name) {
    $sql_autoservice_info = "SELECT phone_autoservice, name_city, address, MIN(price) as min_price, MAX(price) as max_price 
      FROM public.autoservice_service JOIN public.autoservice USING(autoservice_id) JOIN public.city USING(city_id) 
      WHERE autoservice_id = " . $id . " GROUP BY phone_autoservice, name_city, address";
    $autoservice_info = $pdo->query($sql_autoservice_info)->fetch();
    if ($autoservice_info['address'] != NULL) {
      $address = $autoservice_info['name_city'] . ', ' . $autoservice_info['address'];
    } else {
      $address = $autoservice_info['name_city'];
    }
    array_push($arResult, [
      'id' => $id,
      'name' => $name,
      'price' => $autoservice_info['min_price'] . '-' . $autoservice_info['max_price'],
      'phone' => $autoservice_info['phone_autoservice'],
      'address' => $address
    ]);
  }
  if (!empty($arResult)) {    
    return $arResult;
  }
  return null;
}


//Подробная информация о сервисном центре по id автосервиса для автовладельца
function getAutoserviceInfoById($autoservice_id) {
  $pdo = conn();
  $sql_autoserv = "SELECT autoservice_id, name_autoservice, phone_autoservice, name_city, address, photos, text 
    FROM public.autoservice JOIN public.city USING(city_id) WHERE autoservice_id = " . $autoservice_id;
  $autoservice = $pdo->query($sql_autoserv)->fetch();
  $services_id = getServicesIdList($autoservice_id);
  $brand_list = getAutoserviceBrands($autoservice_id);
  if ($autoservice['address'] != NULL) {
    $address = $autoservice['name_city'] . ', ' . $autoservice['address'];
  } else {
    $address = $autoservice['name_city'];
  }
  $arResult = [
    'id' => $autoservice['autoservice_id'],
    'name' => $autoservice['name_autoservice'],
    'phone' => $autoservice['phone_autoservice'],
    'address' => $address,
    'photos' => $autoservice['photos'],
    'text' => $autoservice['text'],
    'brand_list' => $brand_list,
    'services_id' => $services_id
  ];
  if (!empty($arResult)) {
    return $arResult;
  }
  return null;
}


//Стоимость услуги по id
function getServicePriceById($autoservice_id, $service_id) {
  $pdo = conn();
  $sql = "SELECT price FROM public.autoservice_service WHERE autoservice_id = " . $autoservice_id . ' AND service_id = ' . $service_id;
  $price = $pdo->query($sql)->fetch()['price'];  
  return $price;
}


//транслитерация слов
function translit($value)
{
	$converter = array(
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya'
	);
  $value = mb_strtolower($value);
	$value = strtr($value, $converter);
	return $value;
}