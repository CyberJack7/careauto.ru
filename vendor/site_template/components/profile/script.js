$("document").ready(function () {
  let btn = document.querySelector("#del_btn");
  btn.onclick = function () {
    let confirmation = confirm(
      "Вы уверены, что хотите удалить все данные вместе с аккаунтом?"
    );
    if (confirmation) {
      let req = new XMLHttpRequest();
      req.open("POST", "account_delete.php"); //файл удаления аккаунта
      req.send(null);
      location.href = "/"; //переход на главную страницу
    }
  };

  let reset_requisites_btn = document.querySelector("#reset_requisites");
  reset_requisites_btn.onclick = function () {
    let req = new XMLHttpRequest();
    req.open(
      "POST",
      "/vendor/site_template/components/profile/delete_requisites.php"
    ); //файл удаления реквизитов аккаунта
    req.send(null);
    location.href = "/profile/"; //переход на главную страницу
  };
});

var expanded = true;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (expanded) {
    checkboxes.style.display = "block";
    expanded = false;
  } else {
    checkboxes.style.display = "none";
    expanded = true;
  }
}
