<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;

function cars_list($user_id) {
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