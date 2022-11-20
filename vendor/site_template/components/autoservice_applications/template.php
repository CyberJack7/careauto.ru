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
    </div>
</div>

<!-- для заявок end -->
<div class="container text-center my-5">
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p><div class="alert alert-success" role="alert">
        ' . $_SESSION['message'] . '</div></p>';
    }
    unset($_SESSION['message']);
    ?>
</div>