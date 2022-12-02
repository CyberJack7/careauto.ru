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
        return NULL;
    }
}


//получение информации о сервисном центре по id и передача в js
if (isset($_POST['autoserv_get_info'])) {
    $autoservice_id = json_decode($_POST['autoserv_get_info'], true);
    if (!empty($autoservice_id)) {
        $autoservice = getAutoserviceInfoById($autoservice_id);
        //формирование массива фотографий с путями и названиями
        $autoserv_ar_photos = getPhotosArray($autoservice_id);
        $autoserv_ar_name_photos = getPhotosNames($autoservice_id);
        $ar_photos = [];
        for ($i = 0; $i < count($autoserv_ar_photos); $i++) {
            array_push($ar_photos, ['src' => $autoserv_ar_photos[$i], 'name' => $autoserv_ar_name_photos[$i]]);
        }
        $autoservice['photos'] = $ar_photos;
        //формирование массива услуг с id и названиями
        $services_id = $autoservice['services_id'];
        $ar_services = [];
        foreach ($services_id as $service_id) {
            array_push($ar_services, ['id' => $service_id, 'name' => getServiceNameById($service_id)]);
        }
        $autoservice['services'] = $ar_services;
        unset($autoservice['services_id']);
        //преобразование в формат для вывода на экран
        foreach ($autoservice as &$value) {
            if ($value == NULL) {
                $value = '-';
            }
        }
        $json_autoservice = json_encode($autoservice);
        echo $json_autoservice;
    } else {
        return NULL;
    }
}


//получение информации об услуге по id 
if (isset($_POST['autoserv_service_id'])) {
    $data = json_decode($_POST['autoserv_service_id'], true);
    if (!empty($data)) {
        $services = getServiceInfo($data['autoservice_id'], $data['service_id']);
        foreach ($services as &$value) {
            if ($value == NULL) {
                $value = '-';
            }
        }
        $json_services = json_encode($services);
        echo $json_services;
    } else {
        return NULL;
    }
}