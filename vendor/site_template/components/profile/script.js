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
});

var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}
