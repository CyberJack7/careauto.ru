<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;

function cars_list($user_id)
{
  $pdo = conn();
  $sql = "SELECT auto_id FROM Public.automobile WHERE client_id = " . $user_id;
  $cars = $pdo->query($sql); //список авто по id
  if (empty($cars)) {
    echo '<p><div class="alert alert-primary" role="alert">Добавьте свой первый автомобиль!</div></p>';
  } else {
    $count = 0;
    while ($row = $cars->fetch()) { //для каждого авто
      $count++;
      $sql_auto = "SELECT name_brand, name_model FROM automobile
          JOIN brand USING(brand_id) JOIN model USING(model_id) 
          WHERE auto_id = " . $row['auto_id'];
      $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
      echo '<a class="list-group-item list-group-item-action" href="#list-item-' . $count . '">'
        . $auto['name_brand'] . ' ' . $auto['name_model'] . '</a>';
    }
  }
}
function appl_list($user_id, $status)
{
  $pdo = conn();

  $sql = "SELECT application_id FROM Public.application WHERE autoservice_id=" . $user_id . " AND status = " . "$status";
  $appl = $pdo->query($sql);
  if (empty($appl)) {
    echo '<p><div class="alert alert-primary" role="alert">Заявок нет!</div></p>';
  } else {
    $count = 0;
    while ($row = $appl->fetch()) {
      $count++;
      $sql_auto = "SELECT name_brand, name_model FROM automobile
          JOIN brand USING(brand_id) JOIN model USING(model_id) 
          WHERE application_id=" . $row['application_id'] . " AND auto_id = " . $row['auto_id'];
      $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
      $sql_client = "SELECT name_client,phone_client FROM Public.client JOIN Public.application USING(client_id)
      WHERE application_id=" . $row['application_id'];
      $client = $pdo->query($sql_client)->fetch(); // ФИО и телефон клиента
      $sql_serv = "SELECT autoserv_serv_id FROM Public.application WHERE application_id = " . $row['application_id'];
      $serv = $pdo->query($sql_serv)->fetch(); // Извлекаеаем массив услуг из заявки
      while ($row_serv = $serv->fetch()) {
        $sql_serv_name = "SELECT name_service FROM Public.service WHERE service_id = " . $row_serv['autoserv_serv_id'];
        $serv[] = $pdo->query($sql_serv_name)->fetch(); // Добавляем по одному наименованию услуги в массив
      };
      $sql_appl = "SELECT date,text,price FROM Public.application WHERE application_id = " . $row['application_id'];
      $appl_info = $pdo->query($sql_appl)->fetch(); // Дата, комментарий, цена
      echo '<a class="list-group-item list-group-item-action" href="#list-item-' . $count . '">'
        . $client['name_client'] . ' ' .
        $client['phone_client'] . ' ' .
        $appl_info['price'] . ' ' .
        $appl_info['date'] . ' ' .
        $auto['name_brand'] . ' ' .
        $auto['name_model'] . ' ' .
        $appl_info['text'] . ' ';
      foreach ($serv as $serv_name) {
        echo $serv_name . ' ';
      }
      echo '</a>';
    }
  }
}