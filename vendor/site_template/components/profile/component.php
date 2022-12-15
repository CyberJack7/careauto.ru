<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

function main_contacts() {
  $template =   
  '<div class="mb-3">
      <label class="form-label" for="email">Адрес электронной почты</label>
      <input class="form-control" id="email" name="email" type="email" value="' . $_SESSION['user']['email'] . '" placeholder="Эл. почта" 
          aria-describedby="emailHelp" required onchange="validInput(this)"/>
  </div>
  <div class="mb-3">
      <label class="form-label" for="phone">Номер телефона</label>
      <input class="form-control" id="phone" name="phone" type="text" value="' . $_SESSION['user']['phone'] . '" placeholder="Номер телефона" 
          data-phone-pattern required onchange="validInput(this)"/>
  </div>
  <div class="mb-3">
      <label class="form-label" for="city">Город</label>
      <select class="form-select" id="city_id" name="city_id" aria-label="Default select example">
          <option value=';
  if ($_SESSION['user']['city_id'] !== null) {
    $template .= '"' . $_SESSION['user']['city_id'] . '" selected>';
  } else {
    $template .= '"" disabled selected>';
  }
  if ($_SESSION['user']['city_id'] !== null) {
    $template .= getCityNameById($_SESSION['user']['city_id']) . '</option>';
  } else {
    $template .= 'Выберите город</option>';
  }
  //вывод списка городов
  echo $template;
  $arResult = city_list();
  foreach ($arResult['CITIES'] as $city_id => $arCity) {
    echo '<option value="' . $city_id . '">' . $arCity['NAME'] . '</option>';
  }
  echo '</select></div>';
}