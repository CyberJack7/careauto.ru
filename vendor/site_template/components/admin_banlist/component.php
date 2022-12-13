<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
require_once PATH_QUERIES;
if (!isset($_SESSION['user']['id'])) {
    session_start();
}


if (!empty($_POST['unban_id'])) {
    unban_user($_POST['unban_id']);
}