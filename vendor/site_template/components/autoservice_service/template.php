<?php require_once __DIR__ . '/component.php';
?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_service/script.js">
</script>

<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_service/style.css">
<?php
//на странице заявок автосервиса может находиться только автосервис
if (!($_SESSION['user']['user_type'] == 'autoservice')) {
    header('Location: /');
}
getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>
<div class="container" id="main_con">
    <div id="show_autoserv_serv" class="panel">
        <h4>Просмотр предоставляемых услуг</h4>
        <h5>Список категорий услуг автосервиса</h5>
        <select id="autoserv_category" name="autoserv_category" class="form-select" size="4"
            aria-label="size 4 select example">
            <?php
            $arAutoServCategory = getAutoserviceCategoryList($_SESSION['user']['id']);
            foreach ($arAutoServCategory as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
            ?>
        </select>
        <h5>Список услуг автосервиса</h5>

        <select id="autoserv_service" name="autoserv_service" class="form-select" size="4"
            aria-label="size 4 select example">
            <!-- Сюда вставляем код из ajax запроса, см JS скрипт -->
        </select>
        <h5>Информация об услуге</h5>
        <div class="con" name="service_info" id="service_info">

        </div>
    </div>
    <div id="show_all_serv" class="panel">
        <h4>Добавление новых услуг</h4>
        <h5>Категории услуг</h5>

        <select id="category" name="category" class="form-select" size="4" aria-label="size 4 select example">
            <?php
            $arCategory = get_category_list();
            foreach ($arCategory as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
            ?>
        </select>

        <h5>Список услуг</h5>
        <select id="service" name="service" class="form-select" size="4" aria-label="size 4 select example">
            <!-- Сюда вставляем код из ajax запроса, см JS скрипт -->

        </select>
        <h5>Информация об услуге</h5>
        <div class="con" name="add_service_info" id="add_service_info">

        </div>

    </div>
</div>

<!--  -->
<div class="container text-center my-5">
    <?php //блок вывода сообщений
    if (isset($_SESSION['message']['text'])) {
        if ($_SESSION['message']['type'] == 'success') {
            echo '<p><div class="alert alert-success" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
        } elseif ($_SESSION['message']['type'] == 'warning') {
            echo '<p><div class="alert alert-warning" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
        } elseif ($_SESSION['message']['type'] == 'danger') {
            echo '<p><div class="alert alert-danger" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
        } elseif ($_SESSION['message']['type'] == 'info') {
            echo '<p><div class="alert alert-info" role="alert">
                ' . $_SESSION['message']['text'] . '</div></p>';
        }
    }
    unset($_SESSION['message']['text'], $_SESSION['message']['type']);
    ?>
</div>