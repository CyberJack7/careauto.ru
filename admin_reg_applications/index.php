<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
    // require_once PATH_CONNECT;
    // $pdo = conn();
?>

<?php
    Main::includeComponent('admin_reg_applications');
?>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>