$(document).ready(function () {
  $(document).on("click", "#cancel_btn", function (event) {
    event.preventDefault();
    if (confirm("Вы действительно хотите отклонить заявку?")) {
      var fdata = new FormData();
      fdata.append("status", "cancel");
      fdata.append("email", $('input[name="email"]').val());
      fdata.append(
        "autoserv_temp_id",
        $('input[name="autoserv_temp_id"]').val()
      );
      fdata.append("document", $('input[name="document"').val());
      $.ajax({
        type: "POST",
        url: "/vendor/site_template/components/admin_reg_applications/component.php",
        data: fdata,
        processData: false,
        contentType: false,
        success: function () {
          $("#appl-accordion").load(" #appl-accordion");
        },
      });
    }
  });
  $(document).on("click", "#accept_btn", function (event) {
    event.preventDefault();
    var fdata = new FormData();
    fdata.append("status", "accept");
    fdata.append("email", $('input[name="email"]').val());
    fdata.append("autoserv_temp_id", $('input[name="autoserv_temp_id"]').val());
    fdata.append("document", $('input[name="document"').val());
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/admin_reg_applications/component.php",
      data: fdata,
      processData: false,
      contentType: false,
      success: function () {
        $("#appl-accordion").load(" #appl-accordion");
      },
    });
  });
});
