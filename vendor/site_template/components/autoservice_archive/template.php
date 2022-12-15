<?php require_once __DIR__ . '/component.php';
?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_archive/script.js">
</script>

<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_archive/style.css">
<?php
//на странице заявок автосервиса может находиться только автосервис
if (!($_SESSION['user']['user_type'] == 'autoservice')) {
    header('Location: /');
}
unset($_SESSION['message']['text'], $_SESSION['message']['type']);
getUserBanInfoById($_SESSION['user']['id']); //проверка на бан
?>

<nav>
    <div class="container">
        <h2>Здесь хранится история заявок автосервиса</h2>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-done-tab" data-bs-toggle="tab" data-bs-target="#nav-done"
                type="button" role="tab" aria-controls="nav-done" aria-selected="true">Завершенные</button>
            <button class="nav-link" id="nav-сancel-tab" data-bs-toggle="tab" data-bs-target="#nav-cancel" type="button"
                role="tab" aria-controls="nav-cancel" aria-selected="false">Отклоненные</button>
        </div>
    </div>
</nav>

<div class="container" id="test-id">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab"
            tabindex="0">
            <div class="accordion" id="accordion1">
                <?php
                $history_list = getAutoserviceHistoryById($_SESSION['user']['id'], "Завершено");
                setAutoserviceHistory($history_list, "Завершено");
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-cancel" role="tabpanel" aria-labelledby="nav-cancel-tab" tabindex="0">
            <div class="accordion" id="accordion2">
                <?php
                $history_list = getAutoserviceHistoryById($_SESSION['user']['id'], "Отказ");
                setAutoserviceHistory($history_list, "Отказ");
                ?>
            </div>
        </div>
    </div>
</div>

<!-- для заявок end -->
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