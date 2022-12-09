<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_reg_applications/script.js">
</script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_reg_applications/style.css">

<?php
require_once PATH_CONNECT;
$pdo = conn();
//на странице заявок админа может находиться только админ
if (!($_SESSION['user']['user_type'] == 'admin')) {
    header('Location: /');
}
?>

<div class="container">
    <div id="appl-accordion" class="accordion">
        <?php admin_appl_list() ?>
    </div>
</div>