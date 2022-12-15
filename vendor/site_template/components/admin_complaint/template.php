<?php require_once __DIR__ . '/component.php'; ?>
<script src="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_complaint/script.js">
</script>
<link rel="stylesheet"
    href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/vendor/site_template/components/admin_complaint/style.css">

<?php
require_once PATH_CONNECT;
$pdo = conn();
//на странице заявок админа может находиться только админ
if (!($_SESSION['user']['user_type'] == 'admin')) {
    header('Location: /');
}
?>

<nav>
    <div class="container">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-client-tab" data-bs-toggle="tab" data-bs-target="#nav-client"
                type="button" role="tab" aria-controls="nav-client" aria-selected="true">Жалобы на клиентов</button>
            <button class="nav-link" id="nav-autoservice-tab" data-bs-toggle="tab" data-bs-target="#nav-autoservice"
                type="button" role="tab" aria-controls="nav-autoservice" aria-selected="false">Жалобы на СЦ</button>
        </div>
    </div>
</nav>

<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-client" role="tabpanel" aria-labelledby="nav-client-tab"
            tabindex="0">
            <div id="accordion1" class="accordion">
                <?php
                $flag = false;
                $arComplaints = get_complaints("Не рассмотрена");
                if (empty($arComplaints)) {
                    echo '<div class="alert alert-primary" role="alert">
                    Жалоб нет, пейте побольше чая с лимоном!
                  </div>';
                } else {
                    foreach ($arComplaints as $complaint) {
                        if ($complaint['type_of_inspected'] == "client") {
                            $flag = true;

                            echo '<div id="complaint_"' . $complaint['id'] . '>
                        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-heading' . $complaint['id'] . '">
            <button value="' . $complaint['id'] . '" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $complaint['id'] . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $complaint['id'] . '">' .
                                'Жалоба на ' . $complaint['name_inspected'] . ' от ' . $complaint['date'] .
                                '</button>
                        </h2>
                        <div id="panelsStayOpen-collapse' . $complaint['id'] . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $complaint['id'] . '">
                        <div class="accordion-body">' .
                                'Заявитель: ' . $complaint['name_complainant'] . '</br> ' .
                                'Телефон заявителя: ' . $complaint['phone_complainant'] . '</br> ' .
                                'Почта заявителя: ' . $complaint['email_complainant'] . '</br> ' .
                                'Обвиняемый: ' . $complaint['name_inspected'] . '</br> ' .
                                'Телефон обвиняемого: ' . $complaint['phone_inspected'] . '</br> ' .
                                'Почта обвиняемого: ' . $complaint['email_inspected'] . '</br> ' .
                                'Текст жалобы: ' . $complaint['text'] . '</br>
                            Дата жалобы: ' . $complaint['date'] . '</br>

                            <div class="form-floating" name="text_admin">
                            <textarea class="form-control" placeholder="Укажите причину блокировки" id="adminCommentary_' . $complaint['id'] . '" style="height: 100px"></textarea>
                            <label for="autoserviceCommentary_' . $complaint['id'] . '">Причина блокировки</label>
                            </div>

                            <div class="buttons">
                                <button role="button" name="accept" id="accept_btn_' . $complaint['id'] . '" value="' . $complaint['id'] . '" class="btn btn-primary" type="button" >Заблокировать</button>
                                <button role="button" name="cancel" id="cancel_btn_' . $complaint['id'] . '" value="' . $complaint['id'] . '" class="btn btn-secondary" type="button" >Отклонить жалобу</button>
                            </div>
                            </div></div></div></div>';
                        }
                    }
                    if (!$flag) {
                        echo '<div class="alert alert-primary" role="alert">
                    Жалоб нет, пейте побольше чая с лимоном!
                  </div>';
                    }
                }

                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-autoservice" role="tabpanel" aria-labelledby="nav-autoservice-tab"
            tabindex="0">
            <div class="accordion" id="accordion2">
                <?php
                $flag = false;
                $arComplaints = get_complaints("Не рассмотрена");
                if (empty($arComplaints)) {
                    echo '<div class="alert alert-primary" role="alert">
                    Жалоб нет, пейте побольше чая с лимоном!
                  </div>';
                } else {
                    foreach ($arComplaints as $complaint) {
                        if ($complaint['type_of_inspected'] == "autoservice") {
                            $flag = true;

                            echo '<div id="complaint_"' . $complaint['id'] . '>
                        <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-heading' . $complaint['id'] . '">
                        <button value="' . $complaint['id'] . '" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse' . $complaint['id'] . '" aria-expanded="false" aria-controls="#panelsStayOpen-collapse' . $complaint['id'] . '">' .
                                'Жалоба на ' . $complaint['name_inspected'] . ' от ' . $complaint['date'] .
                                '</button>
                                </h2>
                                <div id="panelsStayOpen-collapse' . $complaint['id'] . '" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading' . $complaint['id'] . '">
                                <div class="accordion-body">' .
                                'Заявитель: ' . $complaint['name_complainant'] . '</br> ' .
                                'Телефон заявителя: ' . $complaint['phone_complainant'] . '</br> ' .
                                'Почта заявителя: ' . $complaint['email_complainant'] . '</br> ' .
                                'Обвиняемый: ' . $complaint['name_inspected'] . '</br> ' .
                                'Телефон обвиняемого: ' . $complaint['phone_inspected'] . '</br> ' .
                                'Почта обвиняемого: ' . $complaint['email_inspected'] . '</br> ' .

                                'Текст жалобы: ' . $complaint['text'] . '</br>
                                Дата жалобы: ' . $complaint['date'] . '</br>
                                <div class="form-floating" name="text_admin">
                                <textarea class="form-control" placeholder="Укажите причину блокировки" id="adminCommentary_' . $complaint['id'] . '" style="height: 100px"></textarea>
                                <label for="autoserviceCommentary_' . $complaint['id'] . '">Причина блокировки</label>
                                </div>
                                <div class="buttons">
                                    <button role="button" name="accept" id="accept_btn_' . $complaint['id'] . '" value="' . $complaint['id'] . '" class="btn btn-primary" type="button" >Заблокировать</button>
                                    <button role="button" name="cancel" id="cancel_btn_' . $complaint['id'] . '" value="' . $complaint['id'] . '" class="btn btn-secondary" type="button" >Отклонить жалобу</button>
                                </div>
                                </div></div></div></div>';
                        }
                    }
                    if (!$flag) {
                        echo '<div class="alert alert-primary" role="alert">
                    Жалоб нет, пейте побольше чая с лимоном!
                  </div>';
                    }
                }

                ?>
            </div>
        </div>
    </div>
</div>