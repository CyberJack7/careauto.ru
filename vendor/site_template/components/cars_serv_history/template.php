<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/authorization/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/authorization/style.css">

<?php
    //на странице истории обслуживаний машин автовладельца может находиться только автовладелец
    if (!($_SESSION['user']['user_type'] == 'client')) {
        header('Location: /');
    }
?>