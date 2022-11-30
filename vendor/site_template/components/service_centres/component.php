<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//получение списка услуг по id категории и передача в js
if (isset($_POST['category_id'])) {
    $category_id = json_decode($_POST['category_id']);
    if (!empty($category_id)) {
        $services = getServicesById($category_id);
        $json_services = json_encode($services);
        echo $json_services;
    } else {
        return null;
    }
}