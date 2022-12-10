<?php require_once __DIR__ . '/component.php';
?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_applications/script.js">
</script>

<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/autoservice_applications/style.css">
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
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new"
                type="button" role="tab" aria-controls="nav-new" aria-selected="true">Ожидает подтверждения</button>
            <button class="nav-link" id="nav-wait-tab" data-bs-toggle="tab" data-bs-target="#nav-wait" type="button"
                role="tab" aria-controls="nav-wait" aria-selected="false">Подтверждено</button>
            <button class="nav-link" id="nav-work-tab" data-bs-toggle="tab" data-bs-target="#nav-work" type="button"
                role="tab" aria-controls="nav-work" aria-selected="false">В работе</button>
            <button class="nav-link" id="nav-done-tab" data-bs-toggle="tab" data-bs-target="#nav-done" type="button"
                role="tab" aria-controls="nav-done" aria-selected="false">Выполнено</button>
            <button id="reset-btn" type="button"><img id="reset-img" src="/images/restart.png" alt=""></button>

        </div>
    </div>
</nav>
<div class="container" id="test-id">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab" tabindex="0">
            <div class="accordion" id="accordion1">
                <?php
                appl_list($_SESSION['user']['id'], "Ожидает подтверждения");
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-wait" role="tabpanel" aria-labelledby="nav-wait-tab" tabindex="0">
            <div class="accordion" id="accordion2">
                <?php
                appl_list($_SESSION['user']['id'], "Подтверждено");
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab" tabindex="0">
            <div class="accordion" id="accordion3">
                <?php
                appl_list($_SESSION['user']['id'], "В работе");
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab" tabindex="0">
            <div class="accordion" id="accordion4">
                <?php
                appl_list($_SESSION['user']['id'], "Выполнено");
                ?>
            </div>
        </div>
        <div id="spinner" class="spinner-border text-primary">
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