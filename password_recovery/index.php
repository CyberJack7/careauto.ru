<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
include_once PATH_QUERIES;
?>

<?php
Main::includeComponent('password_recovery');
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>