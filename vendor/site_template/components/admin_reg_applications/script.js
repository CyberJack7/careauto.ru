$(document).ready(function () {
  $(document).on("click", '[role="button"]', function (event) {
    event.preventDefault();
    var btn_type = this.getAttribute("name"); // считываем тип кнопки cancel/accept
    var appl_id = this.getAttribute("value"); // считываем порядковый номер заявки(count)
    var fdata = new FormData();
    if (btn_type == "cancel") {
      if (confirm("Вы действительно хотите отклонить заявку?")) {
        fdata.append("status", "cancel");
      } else {
        exit();
      }
    } else {
      fdata.append("status", "accept");
    }
    fdata.append("email", $('input[id="email_' + appl_id + '"]').val());
    fdata.append(
      "autoserv_temp_id",
      $('input[id="autoserv_temp_id_' + appl_id + '"]').val()
    );
    fdata.append("document", $('input[id="document_' + appl_id + '"]').val());
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
