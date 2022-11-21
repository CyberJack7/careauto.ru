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
    <form action="admin_check_reg.php" class="admin_check_reg_form" method="post">

        <ol class="list-group list-group-numbered">
            <?php
            $sql = "SELECT autoservice_temp_id, name_autoservice FROM Public.autoservice_in_check
            WHERE status = 'не рассмотрено' ORDER BY autoservice_temp_id asc";
            $autoservice = $pdo->query($sql);
            while ($res_autoservice = $autoservice->fetch()) {
                printf("<li class='list-group-item list_custom'>%s
            <span class=\"autoserv_check\"> 
            <button id='sub' name='autoservice_id' value='%s' type='submit'  class='btn btn-primary'>Загрузить заявку</button>
            <button type='button' class='btn btn-secondary'data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Просмотреть заявку</button></span>
            </li>",  $res_autoservice["name_autoservice"], $res_autoservice["autoservice_temp_id"],);
            }
            ?>
        </ol>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Просмотр заявки</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Название сервисного центра: <?php echo $_SESSION['autoservice_in_check']['name'] . '</br>' ?>
                    Адресс электронной почты: <?php echo $_SESSION['autoservice_in_check']['email'] . '</br>' ?>
                    Номер телефона: <?php echo $_SESSION['autoservice_in_check']['phone'] . '</br>' ?>
                    Документ на проверку:
                    <?php
                    $doc = $_SESSION['autoservice_in_check']['document'];
                    echo "<a target='_blank' href='$doc'> Ссылка на документ</a>" ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/vendor/autoservice_to_base.php" class="btn btn-primary" tabindex="-1" role="button"
                        aria-disabled="true">Утвердить автосервис</a>

                </div>
            </div>
        </div>
    </div>
</div>