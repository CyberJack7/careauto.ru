<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;


$id = $_POST["autoservice_id"];
if (empty($id)) {
    echo "Что то не так";
    print_r($_POST);
} else {
    $pdo = conn();
    $sql = "SELECT name_autoservice,email_autoservice,phone_autoservice,document
    FROM Public.autoservice_in_check
    WHERE autoservice_temp_id ='$id'";

    $result = $pdo->query($sql)->fetch();
    $_SESSION['autoservice_in_check'] = [
        "id"   => $id,
        "name" => $result['name_autoservice'],
        "email" => $result['email_autoservice'],
        "phone" => $result['phone_autoservice'],
        "document" => $result['document']
    ];
    header("Location: /admin_reg_applications/");
}