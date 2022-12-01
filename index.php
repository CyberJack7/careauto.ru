<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>

<?php //неавторизованный пользователь пересылается на страницу авторизации
if (empty($_SESSION['user'])) {
  header('Location: /authorization/');
} elseif ($_SESSION['user']['user_type'] == 'client') {
  header('Location: /my_auto/');
} elseif ($_SESSION['user']['user_type'] == 'autoservice') {
  header('Location: /autoservice_applications/');
} else {
  header('Location: /admin_reg_applications/');
}
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>