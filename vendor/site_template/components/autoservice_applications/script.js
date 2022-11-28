$(document).ready(function () {
  $(document).on("click", "#cancel_btn", function (event) {
    event.preventDefault();
    if (confirm("Вы действительно хотите отклонить заявку?")) {
      var fdata = new FormData();
      fdata.append("appl_id", $('input[name="appl_id"]').val());
      fdata.append("date", $('input[name="date"]').val());
      fdata.append("time", $('input[name="time"').val());
      fdata.append("status", "Отказ");
      $.ajax({
        type: "POST",
        url: "/vendor/site_template/components/autoservice_applications/component.php",
        data: fdata,
        processData: false,
        contentType: false,
        success: function () {
          $("#accordion1").load(" #accordion1");
          $("#accordion2").load(" #accordion2");
          $("#accordion3").load(" #accordion3");
          $("#accordion4").load(" #accordion4");
        },
      });
    }
  });
  $(document).on("click", "#accept_btn", function (event) {
    event.preventDefault();
    var fdata = new FormData();
    fdata.append("appl_id", $('input[name="appl_id"]').val());
    fdata.append("date", $('input[name="date"]').val());
    fdata.append("time", $('input[name="time"').val());
    fdata.append("status", $('input[name="status"').val());
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/autoservice_applications/component.php",
      data: fdata,
      processData: false,
      contentType: false,
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
