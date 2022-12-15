$(document).ready(function () {
  $(document).on("click", '[role="button"]', function (event) {
    event.preventDefault();
    var btn_type = this.getAttribute("name"); // считываем тип кнопки cancel/accept
    var appl_id = this.getAttribute("value"); // считываем порядковый номер заявки(count)
    var fdata = new FormData();
    if (btn_type == "cancel") {
      if (confirm("Вы действительно хотите отклонить заявку?")) {
        fdata.append("status", "Отказ");
      } else {
        exit();
      }
    } else {
      fdata.append("status", $('input[id="status_' + appl_id + '"]').val());
    }
    if ($('input[id="status_' + appl_id + '"]').val() == "Выполнено") {
      var textArea = document.getElementById(
        "autoserviceCommentary_" + appl_id
      );
      if (textArea.value.trim() == "") {
        fdata.append("text_autoservice", null);
      } else {
        fdata.append("text_autoservice", textArea.value);
      }
      var date_payment = document.getElementById("pay_" + appl_id);
      if (date_payment.value == "null") {
        var date_payment_checkbox = document.getElementById(
          "pay_checkbox_" + appl_id
        );
        if (!date_payment_checkbox.checked) {
          alert("Заявка не оплачена!");
          exit();
        } else {
          fdata.append("date_payment", null);
        }
      } else {
        fdata.append("date_payment", date_payment.value);
      }
    }
    fdata.append("appl_id", $('input[id="appl_id_' + appl_id + '"]').val());
    fdata.append("date", $('input[id="date_' + appl_id + '"]').val());
    fdata.append("time", $('input[id="time_' + appl_id + '"]').val());
    fdata.append("price", $('input[id="prices_' + appl_id + '"]').val());
    var ArService = $('input[id="ArServiceNew_' + appl_id + '"]').val();
    var ArService = "{" + ArService + "}";
    if (ArService == "{null}") {
      alert("Вы не выбрали ни одной услуги!");
      return false;
    } else {
      var NotArService = document.getElementById("Not_In_Autoserv_" + appl_id);
      if (NotArService != null) {
        var Children = NotArService.querySelectorAll("input");
        for (let value of Children.values()) {
          if (value.checked) {
            alert("В заявке присутствуют неподобающие услуги!");
            return false;
          }
        }
      } else {
        fdata.append("ArService", ArService);
        $.ajax({
          type: "POST",
          url: "/vendor/site_template/components/autoservice_applications/component.php",
          data: fdata,
          processData: false,
          contentType: false,
          success: function (responce) {
            $("#accordion1").load(" #accordion1");
            $("#accordion2").load(" #accordion2");
            $("#accordion3").load(" #accordion3");
            $("#accordion4").load(" #accordion4");
            // alert(responce);
          },
        });
      }
    }
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
var modalWrap = null;
function getCarHistory(button) {
  if (modalWrap !== null) {
    modalWrap.remove();
  }

  $.post(
    "/vendor/site_template/components/autoservice_applications/component.php",
    {
      appl_numb: button.value,
      get_car: true,
    },
    function (responce) {
      modalWrap = document.createElement("div");
      modalWrap.innerHTML = responce;
      document.body.append(modalWrap);

      var modal = new bootstrap.Modal(modalWrap.querySelector(".modal"));
      modal.show();
    }
  );
}
var modalWrapcomplaint = null;
function showcomplaint(button) {
  if (modalWrapcomplaint !== null) {
    modalWrapcomplaint.remove();
  }
  $.post(
    "/vendor/site_template/components/autoservice_applications/component.php",
    {
      appl_numb: button.value,
      show_complaint: true,
    },
    function (responce) {
      modalWrapcomplaint = document.createElement("div");
      modalWrapcomplaint.innerHTML = responce;
      document.body.append(modalWrapcomplaint);
      var modal = new bootstrap.Modal(
        modalWrapcomplaint.querySelector(".modal")
      );
      modal.show();
    }
  );
}
function sendcomplaint(button) {
  var text = document.getElementById("complaint_" + button.value).value;
  if (text.trim() != "") {
    $.post(
      "/vendor/site_template/components/autoservice_applications/component.php",
      {
        appl_numb: button.value,
        text_complaint: text,
      },
      function (responce) {}
    );
    alert("Жалоба отправлена на рассмотрение");
  } else {
    alert("Введите причину жалобы!");
  }
}

function getStartServices(button) {
  var element = document.getElementById("categories_" + button.value);
  if (button.type == "checkbox") {
    if (button.checked) {
      // Чекбокс активировали
      var ApplServices = document.getElementById(
        "ArServiceNew_" + button.value
      );
      if (ApplServices.value != "null") {
        var ApplServices = ApplServices.value.split(",");
      } else ApplServices = "null";
    } else {
      // чекбокс деактивировали
      var ApplServices = document.getElementById(
        "ArServiceNew_" + button.value
      );
      if (ApplServices.value != "null") {
        var ApplServices = ApplServices.value.split(",");
        var count = ApplServices.length;
      } else {
        ApplServices = "null";
        var count = 0;
      }
      var ServicesCounter = document.getElementById(
        "services_counter_" + button.value
      );
      ServicesCounter.innerHTML = "Выбрано услуг: " + count;
    }
  } else {
    var count;
    var ApplServicesNew = document.getElementById(
      "ArServiceNew_" + button.value
    );
    var ApplServices = document.getElementById("ArService_" + button.value); // Услуги указанные в заявке изначально
    ApplServicesNew.value = ApplServices.value;
    if (ApplServices.value != "null") {
      ApplServices = ApplServices.value.split(","); // Массив этих услуг
      count = ApplServices.length;
    } else {
      ApplServices = "null";
      count = 0;
    }
    var ServicesCounter = document.getElementById(
      "services_counter_" + button.value
    );
    ServicesCounter.innerHTML = "Выбрано услуг: " + count;
  }
  var ArrayOfChildren = element.querySelectorAll("input");
  var ActiveCategoryCount = 0;
  var ArCategory = {};
  ArrayOfChildren.forEach((el) => {
    var str = el.id;
    var cat_id = str.substr(str.indexOf("_") + 1);
    ArCategory[cat_id] = el.checked;
    if (el.checked) ActiveCategoryCount++;
  });
  var CategoryCounter = document.getElementById(
    "category_counter_" + button.value
  );
  CategoryCounter.innerHTML = "Выбрано категорий услуг: " + ActiveCategoryCount;
  $.post(
    "/vendor/site_template/components/autoservice_applications/component.php",
    {
      appl_numb: button.value,
      ArCategory: ArCategory,
      ApplServices: ApplServices,
    },
    function (responce) {
      $('div[id="services_' + button.value + '"]').html(responce);
    }
  );
}
function ServiceCounter(button) {
  var count;
  if (button.checked) {
    // Если активировали чекбокс
    var ArServicesNew = document.getElementById("ArServiceNew_" + button.value); // выбираем html элемент где записаны все выбранные услуги
    var str = button.id;
    if (ArServicesNew.value != "null") {
      // Если значение не равно нул, добавляем услугу в список
      ArServicesNew.value += "," + str.substr(str.indexOf("_") + 1);
    } else {
      // Если значение равно нул
      ArServicesNew.value = str.substr(str.indexOf("_") + 1);
    }
    count = ArServicesNew.value.split(",").length;
  } else {
    // Если деактивировали чекбокс
    // Надо удалить услугу из общего списка услуг
    var str = button.id;
    var ArServicesNew = document.getElementById("ArServiceNew_" + button.value);
    if (ArServicesNew.value != "null") {
      var ApplServices = ArServicesNew.value.split(","); // Делаем массив из услуг
      var serv_id = str.substr(str.indexOf("_") + 1);
      ApplServices.splice(ApplServices.indexOf(serv_id), 1);
      if (ApplServices.length == 0) {
        // Если это была последняя услуга в списке
        ArServicesNew.value = "null";
        count = 0;
      } else {
        count = ApplServices.length;
        ArServicesNew.value = ApplServices.join(",");
      }
    }
  }
  var ServicesCounter = document.getElementById(
    "services_counter_" + button.value
  );
  ServicesCounter.innerHTML = "Выбрано услуг: " + count;
}

function getStartAmount(button) {
  var price = document.getElementById("prices_" + button.value);
  if (button.type == "checkbox") {
    var str = button.id;
    var serv_id = str.substr(str.indexOf("_") + 1);
    var ArServices = serv_id;
    if (button.checked) {
      var operation = "+";
    } else {
      var operation = "-";
    }
  } else {
    var Services = document.getElementById("ArServiceNew_" + button.value);
    if (Services.value != "null") {
      var ArServices = Services.value.split(",");
    } else {
      var ArServices = 0;
    }
    var operation = "start";
  }
  if (price.value == "") {
    price.value = 0;
  }
  $.post(
    "/vendor/site_template/components/autoservice_applications/component.php",
    {
      appl_numb: button.value,
      ArForPrices: ArServices,
      Old_value: price.value,
      Operation: operation,
    },
    function (responce) {
      price.value = responce;
    }
  );
}
