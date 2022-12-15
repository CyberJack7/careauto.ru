$("document").ready(function () {
  let add_auto_btn = document.getElementById("start_add_automobile"); //кнопка отображения области добавления нового автомобиля
  add_auto_btn.onclick = function () {
    let add_auto = document.getElementById("add_auto");
    if (add_auto.style.display == "none") {
      add_auto.style.display = "block";
      let show_autos = document.getElementById("show_autos").getElementsByClassName("show_auto");
      let show_tires = document.getElementsByClassName("show_tires");
      document.getElementById("tires").style.display = "none"; //скрыть панель комплектов резины
      document.getElementById("show_autos").style.display = "none"; //скрыть панель отображения информации об автомобилях
      document.getElementById("change_auto_area").remove(); //скрыть панель редактирования информации об автомобилях
      document.getElementsByClassName('item_active')[0].className = "list-group-item list-group-item-action"; // подсветка акт. полей навигации
      let amount = show_autos.length;
      for (i = 0; i < amount; i++) { //скрыть все комплектов резины внутри и все панели авто
        show_autos[i].style.display = "none";
        show_tires[i].style.display = "none";
      }
    }
  };

  //подсветка полей валидации
  $(":input").on("change", function () {
    var element = this;
    if(element.value == "") {
      $(element).removeClass("req");
    } else {
      $(element).addClass("req");
    }
  });

  //подсветка по нажатию
  $(":submit").on("click", function () {
    $("input[required]").addClass("req");
    $("select[required]").addClass("req");
  });
});

/*function checkValid(div) {
  console.log(div);
  
  div.addEventListener('invalid', function(evt) {
    console.log(evt.target);
  
    evt.target.style.color = '#f00';
  
  }, true)
}*/


//название типа резины по id
function getTireTypeNameById (tire_type_id) {
  let tires_type_name = document.getElementById("tires_type").getElementsByTagName("option");
  let amount = tires_type_name.length;
  for (let i = 0; i < amount; i++) { //название типа резины
    if (tires_type_name[i].value == tire_type_id) {
      return tires_type_name[i].innerHTML;
    }
  }
}


//обновление списка моделей в dropdown
function setModels(brands) {
  let current_models_list = document.getElementById("model").getElementsByTagName("option");
  let amount = current_models_list.length;
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
    if (document.getElementById("change_auto_area")) {
      document.getElementById("change_auto_area").remove()
      document.getElementById("show_autos").style.display = "block";
    }
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
      show_add_tires.parentNode.getElementsByTagName("h3")[0].innerHTML = "Комплекты резины";
      show_add_tires.parentNode.getElementsByTagName("button")[0].style.display = "block";
    }
    document.getElementById("tires_auto_id_" + auto.id).style.display = "block"; //показать комплекты резины
  }
}


//отменить добавление автомобиля
function cancelAddAuto(cancel_add_car) {
  document.getElementById("add_auto").style.display = "none"; //скрыть область добавления нового автомобиля
  document.getElementById("show_autos").style.display = "block"; //показать панель отображения информации об автомобилях
  document.getElementById("tires").style.display = "block"; //показать панель комплектов резины
  let item_active = document.getElementsByClassName("list-group-item");
  if (item_active[0]) {
    item_active[0].className = "list-group-item list-group-item-action item_active";
    document.getElementById("info_auto_id_" + item_active[0].id).style.display = "block";
  }
}


//отобразить область редактирования данных об автомобиле
function showChangeAuto() {
  let auto_id = document.getElementsByClassName("item_active")[0].id;
  document.getElementById("show_autos").style.display = "none";
  //область редактирования
  let change_auto_area = document.getElementById("add_auto").cloneNode(true);
  change_auto_area.id = "change_auto_area";
  change_auto_area.getElementsByTagName("h3")[0].innerHTML = "Редактирование данных<br><b>" + document.getElementById("info_auto_id_" + auto_id)
  .getElementsByTagName("h3")[0].innerHTML + '</b>';
  change_auto_area.removeAttribute("action");
  change_auto_area.removeAttribute("method");

  //кнопка сохранить
  let confirm_change_btn = change_auto_area.getElementsByClassName("btn btn-primary")[0];
  confirm_change_btn.id = "confirm_change_btn";
  confirm_change_btn.type = "submit";
  confirm_change_btn.innerHTML = "Сохранить";  

  //кнопка удалить
  let cancel_change_btn = change_auto_area.getElementsByClassName("btn btn-secondary")[0];
  cancel_change_btn.id = "cancel_change_btn";
  cancel_change_btn.type = "button";

  //формирование индексов по типам input/select текущих значений
  let mb_3 = change_auto_area.getElementsByClassName("mb-3");
  mb_3[0].remove();
  mb_3[0].remove();
  let amount_mb_3 = mb_3.length;
  let input_index = [];
  let select_index = [];
  for (let i = 0; i < amount_mb_3; i++) {
    if (typeof mb_3[i].getElementsByTagName("input")[0] != "undefined") {
      input_index.push(i);
    } else {
      select_index.push(i);
    }
  }
  
  //добавление значений в область
  let cur_auto_value_p = document.getElementById("info_auto_id_" + auto_id).getElementsByClassName("value")[0].getElementsByTagName("p");
  let amount_input = input_index.length;
  for (let i = 0; i < amount_input; i++) {
    mb_3[input_index[i]].getElementsByTagName("input")[0].value = cur_auto_value_p[input_index[i]].innerHTML;
    mb_3[input_index[i]].getElementsByTagName("input")[0].className = "form-control req";
    if (mb_3[input_index[i]].getElementsByTagName("input")[0].id == "date_buy") {
      mb_3[input_index[i]].getElementsByTagName("input")[0].id = "change_date_buy";
    }
    if (mb_3[input_index[i]].getElementsByTagName("input")[0].id == "auto_year") {
      mb_3[input_index[i]].getElementsByTagName("input")[0].id = "change_auto_year";
    }
  }
  let amount_select = select_index.length;
  for (let i = 0; i < amount_select; i++) {
    let select = mb_3[select_index[i]].getElementsByTagName("select")[0].getElementsByTagName('option');
    mb_3[select_index[i]].getElementsByTagName("select")[0].className = "form-select req";
    let amount_option = select.length
    for (let j = 0; j < amount_option; j++) {
      if (select[j].innerHTML === cur_auto_value_p[select_index[i]].innerHTML) {
        select[j].selected = true;
        break;
      }
    }
  }
  
  document.getElementById("add_auto").parentNode.appendChild(change_auto_area);
  confirm_change_btn.onclick = function() {
    changeAuto();
  };
  cancel_change_btn.onclick = function() {
    document.getElementById("change_auto_area").remove()
    document.getElementById("show_autos").style.display = "block";
  };
  change_auto_area.style.display = "block";
}


//редактировать данные об автомобиле
function changeAuto() {
  //формирование массива данных о комплекте
  let ar_change_auto = [];
  let auto_id = document.getElementsByClassName('item_active')[0].id; //id подсвеченного поля навигации
  ar_change_auto[0] = auto_id;
  let mb_3_values = document.getElementById("change_auto_area").getElementsByClassName("mb-3");
  let amount_mb_3 = mb_3_values.length;
  let data_correct = true;
  for (let i = 0; i < amount_mb_3; i++) {
    if (mb_3_values[i].getElementsByTagName("input")[0]) {
      if (!mb_3_values[i].getElementsByTagName("input")[0].checkValidity()) {
        data_correct = false;
      }
      ar_change_auto[i + 1] = mb_3_values[i].getElementsByTagName("input")[0].value;
    } else {
      if (!mb_3_values[i].getElementsByTagName("select")[0].checkValidity()) {
        data_correct = false;
      }  
      ar_change_auto[i + 1] = mb_3_values[i].getElementsByTagName("select")[0].value;
    }
    if (!data_correct) {
      break;
    }
  }
  if (data_correct) {
    //отправка массива на обработку
    let json_ar_change_auto = JSON.stringify(ar_change_auto);
    $.post("/vendor/site_template/components/my_auto/component.php", {change_auto: json_ar_change_auto});
  }
}


//удалить автомобиль
function deleteAuto() {
  let auto_id = document.getElementsByClassName("item_active")[0].id;
  let confirmation = confirm("Удалить данные об автомобиле " + document.getElementById("info_auto_id_" + auto_id).getElementsByTagName("h3")[0].innerHTML + "?");
  if (confirmation) {
    let json_auto_id = JSON.stringify(auto_id);    
    $.post("/vendor/site_template/components/my_auto/component.php", {delete_auto: json_auto_id});
    location.reload();
  }
}


//развернуть подробную информацию о комплекте резины
function showTires(show_tires_div) {
  let tires_id = parseInt(show_tires_div.id.match(/\d+/), 10);
  if (!document.getElementById("change_tires_area_" + tires_id)) {
    let info_area = show_tires_div.parentNode.parentNode.getElementsByClassName("central")[0];
    if (info_area.style.display == "none") {
      info_area.style.display = "flex";
      show_tires_div.getElementsByTagName("img")[0].style.transform = 'rotate(180deg)';
    } else {
      info_area.style.display = "none";
      show_tires_div.getElementsByTagName("img")[0].style.transform = 'rotate(0deg)';
    }
  }
}


//отобразить область добавления нового комплекта резины
function showAddTires(show_add_tires_btn) {
  let cur_auto_id = document.getElementsByClassName('item_active')[0].id; //id подсвеченного поля навигации
  let show_tires = document.getElementById("tires_auto_id_" + cur_auto_id);
  show_tires.style.display = "none";
  show_tires.parentNode.getElementsByTagName("h3")[0].innerHTML = "Добавление комплекта резины";
  document.getElementById("show_add_tires").style.display = "none";  
  document.getElementById("add_tires").style.display = "block";
}


//добавлениe нового комплекта резины и отображение области информации о комплектах резины
function addTires(add_tires_btn) {  
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
    let year = 0;
    let tires_date_buy = document.getElementById("tires_date_buy").value;
    if (tires_date_buy != "") {
      year = tires_date_buy.substring(0, tires_date_buy.indexOf("-"));
    }
    let today = new Date();
    let cur_year = today.getFullYear();
    if ((tires_date_buy == "") || (tires_date_buy != "" && year > 1886 && year <= cur_year)) {
      document.getElementById("add_tires").style.display = "none";
      let marking = document.getElementById("marking");
      ar_add_tires[2] = marking.value;
      marking.value = null;
      ar_add_tires[3] = tires_date_buy;
      tires_date_buy = null;
      ar_add_tires[4] = cur_auto_id;
  
      let ar_add_tires_copy = ar_add_tires.slice(0);      
      let tires_id = 0;
    
      //добавление комплекта в область
      let cloned_plate = document.getElementsByClassName("plate")[0];
      if(cloned_plate.getElementsByTagName("div")[6]) {
        cloned_plate.removeChild(cloned_plate.getElementsByTagName("div")[6]);        
      }
      if (typeof cloned_plate != "undefined") { //скопировали структуру плашки с резиной, добавили и заполнили нужной инфой
        cloned_plate = cloned_plate.cloneNode(true);
        ar_add_tires_copy[1] = getTireTypeNameById(ar_add_tires_copy[1]);
        cloned_plate.id = "tires_id_" + tires_id;
        cloned_plate.getElementsByTagName("h5")[0].innerHTML = ar_add_tires_copy[0];
        cloned_plate.getElementsByClassName("central")[0].style.display = "none";
        let text_list_value_p = cloned_plate.getElementsByClassName("value")[0].getElementsByTagName("p");
        for (let i = 1; i < 4; i++) {
          if (ar_add_tires_copy[i] != "") {
            text_list_value_p[i-1].innerHTML = ar_add_tires_copy[i];
          } else {
            text_list_value_p[i-1].innerHTML = "-";
          }
        }
        let cur_tires_area = document.getElementById("tires_auto_id_" + cur_auto_id);
        cur_tires_area.appendChild(cloned_plate);
      } else { //если комплектов нет, создаём первый
        location.reload();
      }
  
      let message_area = document.getElementById("tires_auto_id_" + cur_auto_id).getElementsByClassName("alert alert-info")[0];
      if (typeof message_area != "undefined") {
        message_area.remove();
      }
      document.getElementById("tires_auto_id_" + cur_auto_id).style.display = "block"; //отображение области информации о комплектах резины
      document.getElementById("show_add_tires").style.display = "block";
    
      //отправка массива на обработку
      let json_ar_add_tires = JSON.stringify(ar_add_tires);
      $.post("/vendor/site_template/components/my_auto/component.php", {tires: json_ar_add_tires}, 
        function (data) { //функция которая будет выполнена после успешного запроса
        tires_id = JSON.parse(data); //конвертировали JSON string из data в js object
        let cur_plate = document.getElementById("tires_id_0");
        cur_plate.id = "tires_id_" + tires_id;
        cur_plate.getElementsByClassName("dropdown_img")[0].id = "tires_" + tires_id;
        cur_plate.getElementsByClassName("edit_img")[0].id = "tires_" + tires_id;
        cur_plate.getElementsByClassName("delete_img")[0].id = "tires_" + tires_id;
      });
    }
  }
}


//отменить добавлениe нового комплекта резины
function cancelAddTires(cancel_add_tires_btn) {
  let cur_auto_id = document.getElementsByClassName("item_active")[0].id;
  document.getElementById("tires_auto_id_" + cur_auto_id).style.display = "block";
  let show_add_tires = document.getElementById("add_tires");
  show_add_tires.style.display = "none"; //скрыть область добавления нового комплекта резины
  show_add_tires.parentNode.getElementsByTagName("h3")[0].innerHTML = "Комплекты резины";
  document.getElementById("show_add_tires").removeAttribute("style");
  cancel_add_tires_btn.parentNode.parentNode.parentNode.getElementsByClassName("dropdown_img")[0].getElementsByTagName("img")[0].style.transform = 'rotate(0deg)';
}


//редактирование комплекта покрышек
function editTires(tires) {
  let tires_id = parseInt(tires.id.match(/\d+/), 10);
  if (!document.getElementById("change_tires_area_" + tires_id)) {
    //развернуть поле
    showTires(tires.parentNode.parentNode.getElementsByClassName("dropdown_img")[0]);
  
    let tires_area = document.getElementById("tires_id_" + tires_id);
    let central_area = tires_area.getElementsByClassName("central")[0];
    central_area.style.display = "none";
  
    let change_area = document.createElement("div");
    change_area.id = "change_tires_area_" + tires_id;
  
    let value_area = central_area.getElementsByClassName("value")[0];
    //формирование значений, которые вставим в поля редактирования
    let insert_value = []; 
    insert_value[0] = tires_area.getElementsByClassName("btn_div")[0].getElementsByTagName("h5")[0].innerHTML;
    let ar_p = value_area.getElementsByTagName("p");
    let amount_p = ar_p.length;
    for (let i = 0; i < amount_p; i++) {
      insert_value[i+1] = ar_p[i].innerHTML
    }
  
    let input = []; //массив input полей
    let p_inner_text = ["Марка", "Тип резины", "Маркировка", "Дата покупки"];
    let add_tires_area = document.getElementById("add_tires");
    let cloned_mb_3 = add_tires_area.getElementsByClassName("mb-3");
    let amount_cloned_mb_3 = cloned_mb_3.length;
    for (let i = 0; i < amount_cloned_mb_3; i++) {
      if (typeof cloned_mb_3[i].getElementsByTagName("input")[0] != "undefined") {
        input[i] = cloned_mb_3[i].getElementsByTagName("input")[0].cloneNode(true);
        input[i].id = "change_" + cloned_mb_3[i].getElementsByTagName("input")[0].id;
        input[i].className = "form-control req";
      } else {
        input[i] = cloned_mb_3[i].getElementsByTagName("select")[0].cloneNode(true);
        input[i].id = "change_" + cloned_mb_3[i].getElementsByTagName("select")[0].id;
        input[i].className = "form-control req";
      }
    }
    input[0].required = true;
    input[1].required = true;
    
    let select = input[1].getElementsByTagName('option');
    let amount_option = select.length
    for (let i = 0; i < amount_option; i++) {
      if (select[i].innerHTML === insert_value[1]) {
        select[i].selected = true;
        break;
      }
    }
  
    //добавление полей в область редактирования
    for (let i = 0; i < input.length; i++) {
      if (i != 1) {
        input[i].value = insert_value[i];
      }
      let div = document.createElement("div");
      div.className = "info";
      let p_inner = document.createElement("p");
      p_inner.innerHTML = p_inner_text[i];
      div.appendChild(p_inner);
      div.appendChild(input[i]);
      change_area.appendChild(div);
    }
    
    //кнопки сохранить и отменить
    let change_tires_btn = document.getElementById("tires_id_" + tires_id).parentNode.parentNode.getElementsByTagName("button")[0].cloneNode(true);
    let cancel_change_btn = change_tires_btn.cloneNode(true);
    let btn_div = document.createElement("div");
    btn_div.className = "btn_div";
    
    change_tires_btn.id = "change_tires_" + tires_id;
    change_tires_btn.innerHTML = "Сохранить";
    
    cancel_change_btn.id = "cancel_change_tires_" + tires_id;
    cancel_change_btn.className = "btn btn-secondary";
    cancel_change_btn.innerHTML = "Отменить";
    cancel_change_btn.onclick = function() {
      cancel_change_btn.parentNode.parentNode.remove();
      showTires(document.getElementById("tires_id_" + tires_id).getElementsByClassName("dropdown_img")[0]);
    };
    
    btn_div.appendChild(change_tires_btn);
    btn_div.appendChild(cancel_change_btn);
    change_area.appendChild(btn_div);
    central_area.parentNode.appendChild(change_area);
    change_tires_btn.onclick = function() {
      confirmChangeTires(tires_id);
    };
  } else {
    document.getElementById("change_tires_area_" + tires_id).remove();
    showTires(document.getElementById("tires_id_" + tires_id).getElementsByClassName("dropdown_img")[0]);
  }
}


//сохранение изменений комплекта резины
function confirmChangeTires(tires_id) {
  if (document.getElementById("change_tires_brand").checkValidity() && document.getElementById("change_tires_type").checkValidity() 
    && document.getElementById("change_marking").checkValidity() && document.getElementById("change_tires_date_buy").checkValidity()) {
    //формирование массива изменённых данных
    let change_tires_area = document.getElementById("change_tires_area_" + tires_id);
    let info_div = change_tires_area.getElementsByClassName("info");
    let amount_info = info_div.length;
    let changed_values = [];
    for (let i = 0; i < amount_info; i++) {
      if (typeof info_div[i].getElementsByTagName("input")[0] != "undefined") {
        changed_values[i] = info_div[i].getElementsByTagName("input")[0].value;
      } else {
        let select = info_div[i].getElementsByTagName("select")[0];
        changed_values[i] = select.options[select.selectedIndex].value;
      }
    }
    //отправка массива на обработку
    let json_changed_values = JSON.stringify({
      tires_id: tires_id, 
      brand: changed_values[0],
      tire_type_id: changed_values[1],
      marking: changed_values[2],
      date_buy: changed_values[3],
    });
    $.post("/vendor/site_template/components/my_auto/component.php", {change_tires: json_changed_values});
  
    //изменение данных в области отображения
    change_tires_area.remove();
    let tires_area = document.getElementById("tires_id_" + tires_id);
    tires_area.getElementsByTagName("h5")[0].innerHTML = changed_values[0];
    let value_area_p = tires_area.getElementsByClassName("value")[0].getElementsByTagName("p");    
    let amount_value_p = value_area_p.length;
    for (let i = 0; i < amount_value_p; i++) {
      if (changed_values[i + 1] != "") {
        value_area_p[i].innerHTML = changed_values[i + 1];
      } else {
        value_area_p[i].innerHTML = "-";
      }
    }
    value_area_p[0].innerHTML = getTireTypeNameById(changed_values[1]);
    showTires(document.getElementById("tires_id_" + tires_id).getElementsByClassName("dropdown_img")[0]);
  }
}


//удаление комплекта резины
function deleteTires(tires) {
  let confirmation = confirm("Удалить комплект покрышек " + tires.parentNode.parentNode.getElementsByTagName("h5")[0].innerHTML + "?");
  if (confirmation) {
    let tires_id = parseInt(tires.id.match(/\d+/), 10);
    let auto_id = parseInt(tires.parentNode.parentNode.parentNode.parentNode.id.match(/\d+/), 10);
    let json_del_tires = JSON.stringify({auto_id: auto_id, tires_id: tires_id});
    let data_send = { del_tires: json_del_tires };
    getAjax("/vendor/site_template/components/my_auto/component.php", data_send);

    document.getElementById("tires_id_" + tires_id).remove();

    let count = document.getElementById("tires_auto_id_" + auto_id).getElementsByClassName("plate").length;

    if (count == 0) {
      let message_area = document.createElement("div");
      message_area.className = "alert alert-info";
      message_area.role = "alert";
      message_area.innerHTML = "Список комплектов резины пуст";
      let cur_tires_area = document.getElementById("tires_auto_id_" + auto_id);
      cur_tires_area.insertBefore(message_area, cur_tires_area.getElementsByClassName("btn btn-primary")[0]);
    }  
  }
}


//синхронизировать год выпуска и дату покупки
function synchDateBuy(auto_year) {
  if (auto_year.id == "auto_year") {
    if (auto_year.value != "") {
      document.getElementById("date_buy").setAttribute("min", auto_year.value + "-01-01");
    } else {
      document.getElementById("date_buy").setAttribute("min", "1886-01-29");
    }
  } else {
    if (auto_year.value != "") {
      document.getElementById("change_date_buy").setAttribute("min", auto_year.value + "-01-01");
    } else {
      document.getElementById("change_date_buy").setAttribute("min", "1886-01-29");
    }
  }
}


function validInput(input) {
  if (input.value != "") {
      input.className = "form-control req";        
  } else {
      input.className = "form-control";
  }
}