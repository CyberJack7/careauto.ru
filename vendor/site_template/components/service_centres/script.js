
//обновление количества выбранных услуг
function setServiceAmount() {
  let title = document.getElementById("show_services").getElementsByTagName("option")[0];
  let checkboxes = document.getElementById("services").getElementsByTagName("input");
  len_cb = checkboxes.length;
  amount = 0;
  for (let i = 0; i < len_cb; i++) {
    if (checkboxes[i].checked) {
      amount++;
    }
  }
  title.innerHTML = "Выбрано услуг: " + amount;
}


//обновление списка услуг в checkboxes
function setServices(ar_services, type_of_change, category_id) {
  let amount_services = ar_services.length;
  let services_list = document.getElementById("services");
  if (type_of_change) { //добавляем список услуг
    let div = document.createElement("div");
    div.className = "services_by_catedory";
    div.id = category_id;
    let p = document.createElement("p");
    p.id = category_id;
    let labels =  document.getElementById("categories").getElementsByTagName("label");
    let amount_labels = labels.length;
    for (let i = 0; i < amount_labels; i++) {
      if (labels[i].getElementsByTagName("input")[0].id == category_id) {
        p.innerHTML = '<b>' + labels[i].innerText.trim() + '</b>';
        break;
      }
    }
    div.append(p);
    for (let i = 0; i < amount_services; i++) {
      let label = document.getElementById("categories").getElementsByTagName("label")[0].cloneNode(true);
      label.setAttribute('for', ar_services[i]["id"]);      
      let input = label.getElementsByTagName("input")[0];
      input.id = ar_services[i]["id"];
      input.checked = false;
      input.removeAttribute("onclick");
      label.innerText = "";
      label.append(input);
      label.append(ar_services[i]["name"]);
      div.append(label);
      label.onclick = function() {
        setServiceAmount();
      }
    }
    services_list.append(div);    
  } else { //убираем их списка услуг
    let div = services_list.getElementsByClassName("services_by_catedory")
    let amount_div = div.length;
    for (let i = 0; i < amount_div; i++) {
      if (div[i].id == category_id) {
        div[i].remove();
        break;
      }
    }
    setServiceAmount();
  }
}


//получение списка услуг по id категории
function getServicesById(category) {
  let category_id = category.id; //id нажатой категории
  let type_of_change = true; //тип изменения: true - добавить в список услуг, false - очистить из списка услуг
  let text = document.getElementById("show_categories").getElementsByTagName("option")[0];
  let amount_checked_categories = parseInt(text.innerHTML.match(/\d+/), 10);
  if (category.checked == true) {
    text.innerHTML = "Выбрано категорий услуг: " + (amount_checked_categories + 1);
  } else {
    text.innerHTML = "Выбрано категорий услуг: " + (amount_checked_categories - 1);
    type_of_change = false;
  }
  let json_category_id = JSON.stringify(category_id);
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { category_id: json_category_id },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      let ar_services = JSON.parse(data); //конвертировали JSON string из data в js object
      setServices(ar_services, type_of_change, category_id);
    }
  );
}


//отображение информации об услуге
function setServiceInfo(ar_service_info) {
  let value_p = document.getElementById("service_info").getElementsByClassName("value")[0].getElementsByTagName("p")
  let count = 0;
  for (key in ar_service_info) {
    value_p[count].innerHTML = ar_service_info[key];
    count++;
  }  
}


//получение информации об услуге
function getServiceInfo(select) {
  let service_id = select.options[select.selectedIndex].value; //id выбранной услуги
  let autoservice_id = document.getElementsByClassName("show_autoservice")[0].id;
  let json_data = JSON.stringify({'autoservice_id': autoservice_id, 'service_id': service_id});
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { autoserv_service_id: json_data },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      let ar_service_info = JSON.parse(data); //конвертировали JSON string из data в js object      
      setServiceInfo(ar_service_info);
    }
  );
}