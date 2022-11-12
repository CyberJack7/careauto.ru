$(document).ready(function () {
  //подсветка полей
  $(":submit").on("click", function () {
    $("input[required]").addClass("req");
    $("select[required]").addClass("req");
  });
  $(":radio").on("click", function () {
    $("input[required]").removeClass("req");
    $("select[required]").removeClass("req");
  });

  // переключение кнопок-форм
  $("#" + $(".radio:checked").val()).show();
  $(".btn-check").change(function () {
    $(".radio-blocks").hide();
    $("#" + $(this).val()).show();
  });

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
