$(document).ready(function () {
  let btn = document.querySelector("#logout_btn");
  if (btn) {
    btn.onclick = function () {
      let req = new XMLHttpRequest();
      req.open(
        "POST",
        "/vendor/site_template/components/navigation.main/logout.php"
      ); //файл удаления $_SESSION['user']
      req.send(null);
      location.href = "/"; //переход на главную страницу
    };
  }
});
