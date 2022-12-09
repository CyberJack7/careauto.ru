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
});
