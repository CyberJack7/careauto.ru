<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//получение списка услуг по id категории и передача в js
if (isset($_POST['delete_application'])) {
    $application_id = json_decode($_POST['delete_application'], true);
    $sql_delete_application = "DELETE FROM public.application WHERE application_id = " . $application_id;
    $stmt = $pdo->exec($sql_delete_application);
}

