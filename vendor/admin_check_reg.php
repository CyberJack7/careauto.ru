<?php
session_start();
require_once 'connect.php';


$id = $_POST["autoservice_id"];
if (empty($id)) {
    echo "Что то не так";
    print_r($_POST);
} else {
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
    header("Location: /admin_check_reg_page.php");
}