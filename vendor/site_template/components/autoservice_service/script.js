$(document).ready(function () {
  $(document).on("click", "#edit", function (event) {
    var status = $("#edit").attr("status");
    if (status == "off") {
      $('input[id="certification"]').prop("disabled", false);
      $('input[id="price"]').removeAttr("readonly");
      $('input[id="text"]').removeAttr("readonly");
      document.getElementById("price").setAttribute("class", "form-control");
      document.getElementById("text").setAttribute("class", "form-control");
      $("#edit").attr("status", "on");
      document.querySelector("#edit").textContent = "Сохранить изменения";
    } else {
      if (document.getElementById("price").value != "") {
        $("#edit").attr("status", "off");
        event.preventDefault();
        var fdata = new FormData();
        fdata.append("price", $('input[id="price"]').val());
        fdata.append("text", $('textarea[id="text"]').val());
        fdata.append("service_id", $('select[name="autoserv_service"]').val());
        if ($("#certification")[0].files.length > 0)
          fdata.append("certification", $("#certification")[0].files[0]);
        else fdata.append("certification", null);
        $.ajax({
          type: "POST",
          url: "/vendor/site_template/components/autoservice_service/component.php",
          data: fdata,
          processData: false,
          contentType: false,
          success: function (responce) {
            $('div[name="service_info"]').html(responce);
          },
        });
        $('input[id="certification"]').prop("disabled", true);
        document.getElementById("price").setAttribute("readonly", "true");
        document.getElementById("text1").setAttribute("readonly", "true");
        document
          .getElementById("price")
          .setAttribute("class", "form-control-plaintext");
        document
          .getElementById("text")
          .setAttribute("class", "form-control-plaintext");
        document.querySelector("#edit").textContent = "Редактировать";
      } else {
        document.getElementById("price").setAttribute("class", "form-control req");
      }
    }
  });
  $("#category").on("change", function (event) {
    event.preventDefault();
    var valueSelected = $('select[name="category"]').val();
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/autoservice_service/component.php",
      data: { category_id: valueSelected },
      success: function (responce) {
        $('select[name="service"]').html(responce);
      },
    });
  });
  $("#autoserv_category").on("change", function (event) {
    event.preventDefault();
    var valueSelected = $('select[name="autoserv_category"]').val();
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/autoservice_service/component.php",
      data: { autoserv_category_id: valueSelected },
      success: function (responce) {
        $('select[name="autoserv_service"]').html(responce);
      },
    });
  });

  $("#autoserv_service").on("change", function (event) {
    event.preventDefault();
    var valueSelected = $('select[name="autoserv_service"]').val();
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/autoservice_service/component.php",
      data: { service_id: valueSelected },
      success: function (responce) {
        $('div[name="service_info"]').html(responce);
      },
    });
  });
  $("#service").on("change", function (event) {
    event.preventDefault();
    var valueSelected = $('select[name="service"]').val();
    $.ajax({
      type: "POST",
      url: "/vendor/site_template/components/autoservice_service/component.php",
      data: {
        service_id: valueSelected,
      },
      success: function (responce) {
        $('div[name="add_service_info"]').html(responce);
      },
    });
  });
  $(document).on("click", "#add_service", function (event) {
    if (document.getElementById("add_price").value != "") {
      document.getElementById("add_price").className = "form-control";
      event.preventDefault();
      var fdata = new FormData();
      fdata.append("add_price", $('input[id="add_price"]').val());
      fdata.append("add_text", $('input[id="add_text"]').val());
      fdata.append("add_service_id", $('select[name="service"]').val());
      if ($("#add_certification")[0].files.length > 0)
        fdata.append("add_certification", $("#add_certification")[0].files[0]);
      else fdata.append("add_certification", null);
      $.ajax({
        type: "POST",
        url: "/vendor/site_template/components/autoservice_service/component.php",
        data: fdata,
        processData: false,
        contentType: false,
        success: function (responce) {
          location.reload();
        },
      });
    } else {
      document.getElementById("add_price").className = "form-control req";
    }
  });
  $(document).on("click", "#del_service", function (event) {
    event.preventDefault();
    var fdata = new FormData();
    fdata.append("del_service_id", $('button[id="del_service"]').val());
    if (confirm("Вы действительно хотите удалить услугу?")) {
      $.ajax({
        type: "POST",
        url: "/vendor/site_template/components/autoservice_service/component.php",
        data: fdata,
        processData: false,
        contentType: false,
        success: function (responce) {
          location.reload();
        },
      });
    } else {
    }
  });
});
