<?php
session_start();

require_once 'vendor/connect.php';

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/admin_page.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
    $('#sub').submit(function(e) {
        e.preventDefault();
        // Coding
        $('#myModal').modal('show'); //or  $('#IDModal').modal('hide');
        return false;
    });
    </script>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <title>careauto</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin_check_reg_page.php">Заявки на регистрацию</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin_complaint.php">Жалобы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin_banlist.php">Бан-лист</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Выйти</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="mx-auto">
        <form action="/vendor/admin_check_reg.php" method="post">

            <ol class="list-group list-group-numbered">
                <?php
                $sql = "SELECT autoservice_temp_id, name_autoservice FROM Public.autoservice_in_check
                WHERE status = 'не рассмотрено' ORDER BY autoservice_temp_id asc";
                $autoservice = $pdo->query($sql);
                while ($res_autoservice = $autoservice->fetch()) {
                    printf("<li class='list-group-item'>%s
                <span> 
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
                        $doc = '/careauto.ru' . substr($_SESSION['autoservice_in_check']['document'], 2);
                        echo "<a target='_blank' href='$doc'> Ссылка на документ</a>" ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="/vendor/autoservice_to_base.php" class="btn btn-primary" tabindex="-1"
                            role="button" aria-disabled="true">Утвердить автосервис</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>