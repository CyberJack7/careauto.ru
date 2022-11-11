
<?php
  session_start();
  require_once 'vendor/connect.php';

  if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
  } else {
    $user_id = 23;
  }
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
      <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarSupportedContent" 
        aria-controls="navbarSupportedContent" 
        aria-expanded="false" 
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/my_auto.php">Мои авто</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Сервисные центры</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Заявки</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/client_history.php">История обслуживания</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Написать в техподдержку</a>
          </li>
        </ul>
        <a href="authoriz_page.php" class="btn btn-primary">Профиль</a>
      </div>
    </div>
  </nav>
  
  <div class="container">
    <h1>Мои авто</h1>
    <div class="row">
      <div class="col-3">
        <div id="list-example" class="list-group">
          <?php
            $sql = "SELECT auto_id FROM Public.automobile WHERE client_id = " . $user_id;
            $cars = $pdo->query($sql); //список авто по id
            $count = 0;
            while ($row = $cars->fetch()) { //для каждого авто
              $count++;
              $sql_auto = "SELECT name_brand, name_model FROM automobile
                JOIN brand USING(brand_id) JOIN model USING(model_id) 
                WHERE auto_id = " . $row['auto_id'];
              $auto = $pdo->query($sql_auto)->fetch(); //марка и брэнд авто
              echo '<a class="list-group-item list-group-item-action" href="#list-item-' . $count . '">'
              . $auto['name_brand'] . ' ' . $auto['name_model'] . '</a>';
            }
          ?>
        </div>
      </div>
      <div class="col-8">
        <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
          <h4 id="list-item-1">Item 1</h4>
          <p></p>
          <h4 id="list-item-2">Item 2</h4>
          <p>Lorem ipsum dolor sit amet.</p>
        </div>
      </div>
    </div>
      <?php
        if (isset($_SESSION['message'])) {
          echo '<p><div class="alert alert-success" role="alert">' . $_SESSION['message'] . '</div></p>';
        }
        unset ($_SESSION['message']);
      ?>
  </div>

</body>

</html>
