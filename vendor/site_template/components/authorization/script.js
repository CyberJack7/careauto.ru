$("document").ready(function () {
  $(":submit").on("click", function () {
    $("input[required]").addClass("req");
  });
  $(":radio").on("click", function () {
    $("input[required]").removeClass("req");
  });
});
