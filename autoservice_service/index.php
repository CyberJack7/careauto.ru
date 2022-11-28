<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
include_once PATH_QUERIES;
?>

<?php
Main::includeComponent('autoservice_service');
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>