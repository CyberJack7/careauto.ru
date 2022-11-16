$(document).ready(function () {
  let btn = document.querySelector("#logout_btn");
  btn.onclick = function () {
    let req = new XMLHttpRequest();
    req.open("POST", "logout.php"); //файл удаления $_SESSION['user']
    req.send(null);
    location.href = "/"; //переход на главную страницу
  };
});
