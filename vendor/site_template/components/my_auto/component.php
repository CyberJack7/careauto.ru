<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

//вывод моделей авто в соответствии с выбранной маркой
if (isset($_POST['brand_id'])) {
    $brand_id = json_decode($_POST['brand_id']);
    if (!empty($brand_id)) {
        $models = modelById($brand_id);
        $json_models = json_encode($models);
        echo $json_models;
    } else {
        return null;
    }
}