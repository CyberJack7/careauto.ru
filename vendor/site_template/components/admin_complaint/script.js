$(document).ready(function () {
  $(document).on("click", '[role="button"]', function (event) {
    event.preventDefault();
    var btn_type = this.getAttribute("name"); // считываем тип кнопки cancel/accept
    var complaint_id = this.getAttribute("value"); // считываем id жалобы
    var fdata = new FormData();
    if (btn_type == "cancel") {
      if (confirm("Вы действительно хотите отклонить жалобу?")) {
        fdata.append("status", "cancel");
      } else {
        exit();
      }
    } else {
      var text = document.getElementById(
        "adminCommentary_" + complaint_id
      ).value;
      if (!text) text = "Нарушение правил пользования сайта";
      fdata.append("text", text);
      fdata.append("status", "accept");
    }
    fdata.append("complaint_id", complaint_id);
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/admin_complaint/component.php",
      data: fdata,
      processData: false,
      contentType: false,
      success: function () {
        $("#accordion1").load(" #accordion1");
        $("#accordion2").load(" #accordion2");
      },
    });
  });
});
