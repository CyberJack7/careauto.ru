function unban(button) {
  var unban_id = button.value;
  var fdata = new FormData();
  fdata.append("unban_id", unban_id);
  if (confirm("Вы точно хотите разблокировать этого пользователя?")) {
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/admin_banlist/component.php",
      data: fdata,
      processData: false,
      contentType: false,
      success: function (responce) {
        $("#accordion1").load(" #accordion1");
        $("#accordion2").load(" #accordion2");
      },
    });
  }
}
