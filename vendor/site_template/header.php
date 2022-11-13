<?php
  session_start();
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/classes/Main.php';
?>

<pre>
    <?php
    print_r($_SESSION);
    ?>
</pre>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/vendor/site_template/style.css">

    <link href="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
    <?php
    /* <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>*/
    ?>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/js/jquery_3.6.1.js"></script> 
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php $_SERVER['DOCUMENT_ROOT']?>/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php $_SERVER['DOCUMENT_ROOT']?>/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php $_SERVER['DOCUMENT_ROOT']?>/images/favicon-16x16.png">
    <link rel="manifest" href="<?php $_SERVER['DOCUMENT_ROOT']?>/images/site.webmanifest">
    <title>careauto</title>
</head>

<body>
<?php
// if (mb_strpos($_SERVER['REQUEST_URI'], '/registration/') === false 
//     && mb_strpos($_SERVER['REQUEST_URI'], '/authorization/') === false) {
//     Main::includeComponent('navigation.main');
// }
Main::includeComponent('navigation.main');
