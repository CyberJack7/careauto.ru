$("document").ready(function () {
  let add_auto_btn = document.getElementById("start_add_automobile"); //кнопка добавления нового автомобиля
  add_auto_btn.onclick = function () {
    let add_auto = document.getElementById("add_auto");
    if (add_auto.style.display == "none") {
      add_auto.style.display = "block";
      let show_autos = document.getElementById("show_autos").getElementsByClassName("show_auto");
      let show_tires = document.getElementsByClassName("show_tires");
      document.getElementById("tires").style.display = "none"; //скрыть панель комплектов резины
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
  let add_auto = document.getElementById("add_auto"); //область добавления нового автомобиля
  if (add_auto.style.display != "none") {
    add_auto.style.display = "none"; //скрыть область добавления нового автомобиля
    document.getElementById("tires").style.display = "block"; //показать панель комплектов резины
  }
  let show_add_tires = document.getElementById("add_tires"); //область добавления нового комплекта резины
  if (show_add_tires.style.display != "none") {
    show_add_tires.style.display = "none"; //скрыть область добавления нового комплекта резины
  }
  let show_auto = document.getElementById("show_auto_" + auto.id); //требуемая область отображения инфы об авто
  let show_autos = document.getElementById("show_autos").getElementsByClassName("show_auto");
  let amount = 0;
  if (show_auto.style.display == 'none') { //если требуемая область скрыта
    amount = show_autos.length;
    for (let i = 0; i < amount; i++) {
      if (show_autos[i].style.display != "none") {
        let current_show_auto_id = show_autos[i].id;
        let len_index = current_show_auto_id.length;
        show_autos[i].style.display = "none"; //скрыть текущую область
        document.getElementById("show_tires_" + current_show_auto_id.substring(len_index - 1)).style.display = "none"; //скрыть комплекты резины
        break;
      }
    }
  }
  show_auto.style.display = "block"; //показать инфу об авто
  document.getElementById("show_tires_" + auto.id).style.display = "block"; //показать комплекты резины
  document.getElementById("show_add_tires").style.display = "block"; //показать кнопку
  document.getElementById("tires").getElementsByTagName('h3')[0].style.display = "block"; //показать заголовок
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
  let tires_info_areas = document.getElementsByClassName("show_tires");
  let amount = tires_info_areas.length;
  for (let i = 0; i < amount; i++) {
    tires_info_areas[i].style.display = "none";
  }
  show_add_tires_btn.style.display = "none";
  document.getElementById("tires").getElementsByTagName("h3")[0].style.display = "none";
  document.getElementById("add_tires").style.display = "block";
}

//добавлениe нового комплекта резины и отображение области информации о комплектах резины
function addTires(add_tires_btn) {
  document.getElementById("add_tires").style.display = "none"; //кнопка добавить

  //формирование массива данных о комплекте
  /*let add_tires = document.getElementById("add_tires");
  let ar_add_tires = [];
  ar_add_tires[0] = document.getElementById("tires_brand").value;
  ar_add_tires[1] = document.getElementById("marking").value;
  let tires_type = document.getElementById("tires_type");
  ar_add_tires[2] = tires_type.options[tires_type.selectedIndex].value;
  ar_add_tires[3] = document.getElementById("tires_date_buy").value;
  ar_add_tires[4] = document.getElementById("show_auto").attributes["value"].value;
  
  //отправка массива на обработку
  let json_ar_add_tires = JSON.stringify(ar_add_tires);
  $.post("/vendor/site_template/components/my_auto/component.php", {tires: json_ar_add_tires});*/

  //отображение области информации о комплектах резины
  document.getElementById("tires").getElementsByTagName("h3")[0].style.display = "block"; //заголовок
  document.getElementById("show_add_tires").style.display = "block"; //кнопка
  let show_autos = document.getElementById("show_autos").getElementsByClassName("show_auto");
  let amount = show_autos.length;
  let amount_index = show_autos[0].id.length;
  let auto_id = 1;
  for (let i = 0; i < amount; i++) {
    if (show_autos[i].style.display != "none") {
      auto_id = show_autos[i].id.substring(amount_index-1);
      break;
    }
  }
  document.getElementById("show_tires_" + auto_id).style.display = "block"; //информация о комплектах конкретного авто

}

function editTires(tires) {
  console.log(tires);
  let info_area = tires.getElementsByClassName("central")[0];
  if (info_area.style.display == "none") {
    info_area.style.display = "flex";
  } else {
    info_area.style.display = "none";
  }
}

function deleteTires(tires) {
  console.log(tires);
  let info_area = tires.getElementsByClassName("central")[0];
  if (info_area.style.display == "none") {
    info_area.style.display = "flex";
  } else {
    info_area.style.display = "none";
  }
}
