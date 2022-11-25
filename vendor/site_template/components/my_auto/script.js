$("document").ready(function () {
  let add_auto_btn = document.getElementById("start_add_automobile"); //кнопка добавления нового автомобиля
  add_auto_btn.onclick = function () {
    let add_auto = document.getElementById("add_auto");
    if (add_auto.style.display == "none") {
      add_auto.style.display = "block";
      let show_autos = document.getElementById("show_autos").getElementsByClassName("show_auto");
      let show_tires = document.getElementsByClassName("show_tires");
      document.getElementById("tires").style.display = "none"; //скрыть панель комплектов резины
      document.getElementById("show_autos").style.display = "none"; //скрыть панель отображения информации об автомобилях
      document.getElementsByClassName('item_active')[0].className = "list-group-item list-group-item-action"; //ыфкл подсветка акт. полей навигации
      let amount = show_autos.length;
      for (i = 0; i < amount; i++) { //скрыть все комплектов резины внутри и все панели авто
        show_autos[i].style.display = "none";
        show_tires[i].style.display = "none";
      }
    }
  };
});


//обновление списка моделей в dropdown
function setModels(brands) {
  let current_models_list = document
    .getElementById("model")
    .getElementsByTagName("option");
  let amount = current_models_list.length;
  console.log(current_models_list);
  for (let i = 1; i < amount; i++) {
    current_models_list[1].remove();
  }
  amount = brands.length;
  let models_list = document.getElementById("model");
  for (let i = 0; i < amount; i++) {
    models_list.append(new Option(brands[i]["name"], brands[i]["id"]));
  }
}


//получение списка моделей по id марки
function getBrandId(brand) {
  let brandId = brand.options[brand.selectedIndex].value; //id выбранной марки
  $.post(
    "/vendor/site_template/components/my_auto/component.php",
    { brand_id: brandId },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      let ar_data = JSON.parse(data); //конвертировали JSON string из data в js object
      setModels(ar_data);
    }
  );
}


//отобразить область информации автомобиля
function showAuto(auto) {
  if (auto.className != "list-group-item list-group-item-action item_active") {
    let add_auto = document.getElementById("add_auto"); //область добавления нового автомобиля
    if (add_auto.style.display != "none") {
      add_auto.style.display = "none"; //скрыть область добавления нового автомобиля
      document.getElementById("show_autos").style.display = "block"; //показать панель отображения информации об автомобилях
      document.getElementById("tires").style.display = "block"; //показать панель комплектов резины
    } else {
      let cur_item_action = document.getElementsByClassName('item_active')[0]; //подсветка активных полей навигации
      cur_item_action.className = "list-group-item list-group-item-action";
      let cur_auto_id = cur_item_action.id;
      document.getElementById("info_auto_id_" + cur_auto_id).style.display = "none"; //скрыть текущую область авто
      document.getElementById("tires_auto_id_" + cur_auto_id).style.display = "none"; //скрыть комплекты резины текущего авто
    }

    auto.className = "list-group-item list-group-item-action item_active"; //подсветить нужное поле
    document.getElementById("info_auto_id_" + auto.id).style.display = "block"; //показать требуемую область отображения инфы об авто
    let show_add_tires = document.getElementById("add_tires"); //область добавления нового комплекта резины
    if (show_add_tires.style.display != "none") {
      show_add_tires.style.display = "none"; //скрыть область добавления нового комплекта резины
    }
    document.getElementById("tires_auto_id_" + auto.id).style.display = "block"; //показать комплекты резины
  }
}


//развернуть подробную информацию о комплекте резины
function showTires(show_tires_area) {
  let info_area = show_tires_area.getElementsByClassName("central")[0];
  if (info_area.style.display == "none") {
    info_area.style.display = "flex";
  } else {
    info_area.style.display = "none";
  }
}


//отобразить область добавления нового комплекта резины
function showAddTires(show_add_tires_btn) {
  let cur_auto_id = document.getElementsByClassName('item_active')[0].id; //id подсвеченного поля навигации
  document.getElementById("tires_auto_id_" + cur_auto_id).style.display = "none";
  document.getElementById("add_tires").style.display = "block";
}


//добавлениe нового комплекта резины и отображение области информации о комплектах резины
function addTires(add_tires_btn) {
  document.getElementById("add_tires").style.display = "none"; //кнопка добавить

  //формирование массива данных о комплекте
  let ar_add_tires = [];
  let cur_auto_id = document.getElementsByClassName('item_active')[0].id; //id подсвеченного поля навигации
  let tires_brand = document.getElementById("tires_brand");
  ar_add_tires[0] = tires_brand.value;
  tires_brand.value = null;
  let tires_type = document.getElementById("tires_type");
  ar_add_tires[1] = tires_type.options[tires_type.selectedIndex].value;
  tires_type.selectedIndex = 0
  if (ar_add_tires[0] != "" && ar_add_tires[1] != "") { //проверка на обязательные поля
    let marking = document.getElementById("marking");
    ar_add_tires[2] = marking.value;
    marking.value = null;
    let tires_date_buy = document.getElementById("tires_date_buy");
    ar_add_tires[3] = tires_date_buy.value;
    tires_date_buy.value = null;
    ar_add_tires[4] = cur_auto_id;
      
    //отправка массива на обработку
    let json_ar_add_tires = JSON.stringify(ar_add_tires);
    let tires_id = 0;
    $.post("/vendor/site_template/components/my_auto/component.php", {tires: json_ar_add_tires}, 
      function (data) { //функция которая будет выполнена после успешного запроса
      tires_id = JSON.parse(data); //конвертировали JSON string из data в js object
    });
  
    //добавление комплекта в область
    let cloned_plate = document.getElementsByClassName("plate")[0];
    if (typeof cloned_plate != "undefined") { //скопировали структуру плашки с резиной, добавили и заполнили нужной инфой
      cloned_plate = document.getElementsByClassName("plate")[0].cloneNode(true);
      let tires_type_name = document.getElementById("tires_type").getElementsByTagName("option");
      let amount = tires_type_name.length;
      for (let i = 0; i < amount; i++) { //название типа резины
        if (tires_type_name[i].value == ar_add_tires[1]) {
          ar_add_tires[1] = tires_type_name[i].innerHTML;
          break;
        }
      }
      cloned_plate.id = tires_id;
      cloned_plate.getElementsByTagName("h5")[0].innerHTML = ar_add_tires[0];
      cloned_plate.getElementsByClassName("central")[0].style.display = "none";
      let text_list_value_p = cloned_plate.getElementsByClassName("value")[0].getElementsByTagName("p");
      for (let i = 1; i < 4; i++) {
        if (ar_add_tires[i] != "") {
          text_list_value_p[i-1].innerHTML = ar_add_tires[i];
        } else {
          text_list_value_p[i-1].innerHTML = "-";
        }
      }
      let cur_tires_area = document.getElementById("tires_auto_id_" + cur_auto_id);
      cur_tires_area.insertBefore(cloned_plate, cur_tires_area.getElementsByClassName("btn btn-primary")[0]);
    } else { //если комплектов нет, создаём первый
      location.reload();
    }
  }
  let message_area = document.getElementById("tires_auto_id_" + cur_auto_id).getElementsByClassName("alert alert-info")[0];
  if (typeof message_area != "undefined") {
    message_area.remove();
  }
  document.getElementById("tires_auto_id_" + cur_auto_id).style.display = "block"; //отображение области информации о комплектах резины
}


function editTires(tires) {
  console.log(tires);
  
}


//удаление комплекта резины
function deleteTires(tires) {
  let tires_id = parseInt(tires.id.match(/\d+/), 10);
  let auto_id = parseInt(tires.parentNode.parentNode.parentNode.parentNode.id.match(/\d+/), 10);
  let json_del_tires = JSON.stringify({auto_id: auto_id, tires_id: tires_id});
  let data_send = { del_tires: json_del_tires };
  getAjax(
    "/vendor/site_template/components/my_auto/component.php", data_send
  );

  document.getElementById("tires_id_" + tires_id).remove();

  let message_area = document.createElement("div");
  message_area.className = "alert alert-info";
  message_area.role = "alert";
  message_area.innerHTML = "Список комплектов резины пуст";
  let cur_tires_area = document.getElementById("tires_auto_id_" + auto_id);
  cur_tires_area.insertBefore(message_area, cur_tires_area.getElementsByClassName("btn btn-primary")[0]);
  
}
