$(document).ready(function () {
  // маска для телефона
  $("[data-phone-pattern]").on("input blur focus", (e) => {
    var el = e.target,
      clearVal = $(el).data("phoneClear"),
      pattern = $(el).data("phonePattern"),
      matrix_def = "+7(___) ___-__-__",
      matrix = pattern ? pattern : matrix_def,
      i = 0,
      def = matrix.replace(/\D/g, ""),
      val = $(el).val().replace(/\D/g, "");
    if (clearVal !== "false" && e.type === "blur") {
      if (val.length < matrix.match(/([\_\d])/g).length) {
        $(el).val("");
        return;
      }
    }
    if (def.length >= val.length) val = def;
    $(el).val(
      matrix.replace(/./g, function (a) {
        return /[_\d]/.test(a) && i < val.length
          ? val.charAt(i++)
          : i >= val.length
          ? ""
          : a;
      })
    );
  });
});

//отправка ajax запроса методом post
function getAjax(url, data_send) {
  $.ajax({
    url: url,
    method: "post",
    dataType: "html",
    data: data_send,
  });
}

//показать/скрыть область отображения чекбоксов
function showCheckboxes(checkboxes_label) {
  let checkboxes =
    checkboxes_label.parentNode.getElementsByClassName("checkboxes")[0];
  if (checkboxes.style.display == "block") {
    checkboxes.style.display = "none";
  } else {
    checkboxes.style.display = "block";
  }
}

//просмотр фотографий
function gallery(photo) {
  let major_photo = document.getElementsByClassName("major_photo")[0];
  major_photo.src = photo.src;
}
