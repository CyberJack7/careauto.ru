
<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
  include_once PATH_QUERIES;

  if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
  } else {
    $user_id = 23;
  }
?>

  <div class="navbar navbar-expand-lg navbar-dark bg-dark navbar_custom">
    <div class="container">
      <a class="navbar-brand p-0" href="/">
        <img src="images/main_title.png" alt="careauto.ru" height="50" />
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
            <a class="nav-link active" aria-current="page" href="my_auto.php">Мои авто</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Сервисные центры</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/">Заявки</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="client_history.php">История обслуживания</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Написать в техподдержку</a>
          </li>
        </ul>
        <a href="" class="btn btn-primary">Профиль</a>
      </div>
    </div>
</div>
  
  <div class="container">
    <h1>Мои авто</h1>
    <div class="row">
      <div class="col-3">
        <div id="list-example" class="list-group">
          <?php
            cars_list($user_id);
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

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>
