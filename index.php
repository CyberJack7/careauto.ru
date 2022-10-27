<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/careauto.ru/assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>
    <title>careauto</title>
</head>


<body>
    <div class="mx-auto">

        <h1>careauto.ru</h1>
        
        <a
            href="authoriz_page.php"
            target="_blank"
            class="btn btn-primary"
          >
            Войти
          </a>

            <?php
                if (isset($_SESSION['result'])) {
                    if ($_SESSION['result'] == 2) {
                        echo '<p><div class="alert alert-success" role="alert">
                        ' . $_SESSION['message'] . '</div></p>';
                    }                    
                }
                unset ($_SESSION['message'], $_SESSION['result']);
                
            ?>
        

</body>

</html>