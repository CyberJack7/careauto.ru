$(document).ready(function () {
  $(document).on("submit", "form", function (event) {
    event.preventDefault();
    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function () {
        $("#accordion1").load(" #accordion1");
        $("#accordion2").load(" #accordion2");
        $("#accordion3").load(" #accordion3");
        $("#accordion4").load(" #accordion4");
      },
    });
  });
  $(document).on("click", "#reset-btn", function (event) {
    document.getElementById("spinner").style.visibility = "visible";
    setTimeout(function () {
      document.getElementById("spinner").style.visibility = "hidden";
    }, 2000);
    $("#accordion1").load(" #accordion1");
    $("#accordion2").load(" #accordion2");
    $("#accordion3").load(" #accordion3");
    $("#accordion4").load(" #accordion4");
  });
});
