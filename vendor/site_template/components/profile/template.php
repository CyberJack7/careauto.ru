<?php require_once __DIR__ . '/component.php';?>
<script src="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/script.js"></script>
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/components/profile/style.css">

<?php //на странице профиля может находиться только авторизованный пользователь
    if (empty($_SESSION['user'])) {
        header('Location: /');
    }
?>