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
      echo '<form action="/vendor/site_template/components/autoservice_applications/component.php" method="post">
      <input name="status" type="hidden" value="' . $status . '"</input>
      <input name="appl_id" type="hidden" value="' . $row['application_id'] . '"</input>
      <button class="btn btn-primary" type="submit" >Подтвердить</button>      
      </form>';
      echo '</div></div></div>';
    }
  }
}