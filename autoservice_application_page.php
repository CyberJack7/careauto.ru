<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/main.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>

    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="/images/site.webmanifest">
    <title>careauto</title>
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand p-0" href="/">
                <img src="/images/main_title.png" alt="careauto.ru" height="50" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!--  -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Заявки</a>
                    </li>
                    <!--  -->
                    <li class="nav-item">
                        <a class="nav-link" href="">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Чаты с клиентами</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Аналитика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Написать в техподдержку</a>
                    </li>
                </ul>
            </div>
            <a href="/autoservice_profile_page.php" class="btn btn-primary">Профиль</a>
        </div>
    </nav>
    <!-- для заявок -->
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
    <div class="container">

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab"
                tabindex="0">
                Это текст с отступом.
            </div>
            <div class="tab-pane fade" id="nav-wait" role="tabpanel" aria-labelledby="nav-wait-tab" tabindex="0">
                Какой то текст 2</div>
            <div class="tab-pane fade" id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab" tabindex="0">
                какой то текст 3</div>
            <div class="tab-pane fade" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab" tabindex="0">
                Какой то текст 4
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
        </dev>

</body>

</html>