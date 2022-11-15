$(document).ready(function () {
  //подсветка полей
  $(":submit").on("click", function () {
    $("input[required]").addClass("req");
    $("select[required]").addClass("req");
  });
});
