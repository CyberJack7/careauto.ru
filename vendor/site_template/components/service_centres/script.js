
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


//поиск и сортировка сервисных центров
function searchAutoservices(search_button) {
  cancelApplication();
  let search_autoservices = document.getElementById("search_autoservices");
  search_autoservices.getElementsByTagName("h3")[0].innerHTML = 'Поиск сервисных центров по Вашему запросу';
  search_autoservices.getElementsByClassName("autoservices_area")[0].remove();
  search_autoservices.insertAdjacentHTML('beforeend', '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');

  let autoservice_name = document.getElementById("autoserv_name").value; //название нужного СЦ
  let autoservice_city = document.getElementById("city").options[document.getElementById("city").selectedIndex].value; //нужный город
  let autoservice_categories = []; //нужные категории
  let autoservice_services = []; //нужные услуги
  let autoservice_auto_id = document.getElementById("autos").options[document.getElementById("autos").selectedIndex].value; //нужный автомобиль
  let autoservice_categories_input = document.getElementById("categories").getElementsByTagName("input");
  let amount_categories_input = autoservice_categories_input.length;
  for (let i = 0; i < amount_categories_input; i++) {
    if (autoservice_categories_input[i].checked == true) {
      autoservice_categories.push(autoservice_categories_input[i].id);
    }
  }
  let autoservice_services_input = document.getElementById("services").getElementsByTagName("input");
  let amount_services_input = autoservice_services_input.length;
  for (let i = 0; i < amount_services_input; i++) {
    if (autoservice_services_input[i].checked == true) {
      autoservice_services.push(autoservice_services_input[i].id);
    }
  }
  let json_data = JSON.stringify({
    'name': autoservice_name,
    'city': autoservice_city,
    'categories': autoservice_categories,
    'services': autoservice_services,
    'auto_id': autoservice_auto_id,
  });
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { search_autoservices: json_data },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      let search_autoservices = document.getElementById("search_autoservices");
      search_autoservices.getElementsByTagName("h3")[0].remove();
      search_autoservices.getElementsByClassName("spinner-border")[0].remove();
      search_autoservices.insertAdjacentHTML('afterBegin', data);
    }
  );
}


//получение информации о сервисном центре
function getAutoserviceInfo(plate) {
  cancelApplication();
  let autoservice_id = parseInt(plate.id.match(/\d+/), 10); //id выбранного СЦ
  let json_autoservice_id = JSON.stringify(autoservice_id);
  let current_autoservice = document.getElementById("current_autoservice");
  current_autoservice.getElementsByClassName("show_autoservice")[0].remove();
  current_autoservice.insertAdjacentHTML('afterBegin', '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');  
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { autoserv_get_info: json_autoservice_id },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      current_autoservice.getElementsByClassName("spinner-border")[0].remove();
      current_autoservice.insertAdjacentHTML('afterBegin', data);
    }
  );
}


//отображение информации об услуге
function setServiceInfo(ar_service_info) {
  let ar_p = document.getElementById("service_info").getElementsByTagName("p");
  ar_p[0].innerHTML = ar_service_info['category'];
  ar_p[1].innerHTML = ar_service_info['price'];
  ar_p[3].innerHTML = ar_service_info['text'];
  let elem_a = document.getElementById("service_info").getElementsByTagName("a")[0];
  elem_a.href = ar_service_info['certification'];
  elem_a.innerHTML = ar_service_info['certification'].substring(ar_service_info['certification'].indexOf('-') + 1);
  if (ar_service_info['certification'] == '-') {
    ar_p[2].style.display = 'block';
    elem_a.style.display = 'none';
  } else {
    ar_p[2].style.display = 'none';
    elem_a.style.display = 'block';
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


//получение информации об услуге
function createApplication(btn) {
  let current_autoservice = document.getElementById("current_autoservice");
  current_autoservice.getElementsByClassName("autoservices_area")[0].style.setProperty("max-height", "193.4px", "important");
  btn.style.display = "none";
  current_autoservice.insertAdjacentHTML('beforeend', '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
  let client_id = document.getElementsByClassName("search")[0].id; //id клиента
  let auto_id = document.getElementById("autos").options[document.getElementById("autos").selectedIndex].value; //id выбранного автомобиля
  let autoservice_id = current_autoservice.getElementsByClassName("show_autoservice")[0].id; //id выбранного СЦ
  let services_inputs = document.getElementById("services").getElementsByTagName("input");
  let amount_services_inputs = services_inputs.length;
  let services_id = [];
  for (let i = 0; i < amount_services_inputs; i++) {
    if (services_inputs[i].checked == true) {
      services_id.push(services_inputs[i].id);
    }
  }
  let json_data = JSON.stringify({'client_id': client_id, 'auto_id': auto_id, 'autoservice_id': autoservice_id, 'services_id': services_id});
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { create_application: json_data },
    function (data) {
      //функция которая будет выполнена после успешного запроса 
      current_autoservice.getElementsByClassName("spinner-border")[0].remove();
      current_autoservice.insertAdjacentHTML('beforeend', data);
    }
  );
}


//рассчитать стоимость заявки и кол-во выбранных услуг
function setPrice(check) {
  let service_id = check.id;
  let autoservice_id = document.getElementById("current_autoservice").getElementsByClassName("show_autoservice")[0].id; //id выбранного СЦ
  let json_data = JSON.stringify({'autoservice_id': autoservice_id, 'service_id': service_id});
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { get_price: json_data },
    function (data) {
      let cur_price_area = document.getElementById("price").getElementsByTagName("b")[0];
      let cur_price = Number(parseInt(cur_price_area.innerHTML.match(/\d+/), 10));
      let cur_amount_services_area = document.getElementById("show_application_services").getElementsByTagName("option")[0];
      let cur_amount_services = Number(parseInt(cur_amount_services_area.innerHTML.match(/\d+/), 10));
      if (check.checked == true) {
        cur_price += Number(data);
        cur_amount_services++;
      } else {
        cur_price -= Number(data);
        cur_amount_services--;
      }
      cur_price_area.innerHTML = '<b>' + String(cur_price) + " р" + '</b>';
      cur_amount_services_area.innerHTML = 'Выбрано услуг: ' + String(cur_amount_services);
    }
  );
}


//отправить заявку
function sendApplication(btn) {
  let client_id = document.getElementsByClassName("search")[0].id; //id клиента
  let auto_id = document.getElementById("auto_application").options[document.getElementById("auto_application").selectedIndex].value; //id выбранного автомобиля
  if (auto_id != '') {
    let autoservice_id = current_autoservice.getElementsByClassName("show_autoservice")[0].id; //id выбранного СЦ  
    let price = Number(parseInt(document.getElementById("price").getElementsByTagName("b")[0].innerHTML.match(/\d+/), 10)); //стоимость
    let date;
    if (document.getElementById("desired_date").value != '') {
      date = document.getElementById("desired_date").value + ' ' + document.getElementById("desired_time").value; //желаемая дата и время
    } else {
      date = document.getElementById("desired_date").value;
    }
    let comment = document.getElementById("comment").value; //комментарий
    let services_id = []; //массив id услуг
    let services_option = document.getElementById("application_services").getElementsByTagName("input");
    let amount_services_option = services_option.length;
    for (let i = 0; i < amount_services_option; i++) {
      if (services_option[i].checked == true) {
        services_id.push(services_option[i].id);
      }
    }
    json_data = JSON.stringify({
      'client_id': client_id, 
      'auto_id': auto_id, 
      'autoservice_id': autoservice_id, 
      'services_id': services_id,
      'price': price,
      'date': date,
      'comment': comment
    });
    $.post(
      "/vendor/site_template/components/service_centres/component.php",
      { send_application: json_data },
      function (data) {
        //функция которая будет выполнена после успешного запроса 
        cancelApplication();
      }
    );
  }
}


//отменить заявку
function cancelApplication() {
  if (document.getElementById("send_application")) {
    document.getElementById("create_application").style.display = 'block';
    document.getElementById("send_application").remove();
    document.getElementById("current_autoservice").getElementsByClassName("autoservices_area")[0].removeAttribute("style");
  }
}



//получение информации о сервисном центре
/*function setAutoservSort(select) {  
  let sort_id = select.options[select.selectedIndex].value;; //id выбранной сортировки
  let json_sort;
  if (sort_id == 0) {
    json_sort = JSON.stringify({sort_id: sort_id});
  } else {
    let plates = document.getElementById("search_autoservices").getElementsByClassName("plate");
    let prices = {};
    let amount_plates = plates.length;
    for (let i = 0; i < amount_plates; i++) {
      let min_price = plates[i].getElementsByTagName("p")[3].innerHTML.substring(0, plates[i].getElementsByTagName("p")[3].innerHTML.indexOf('-'));
      prices[parseInt(plates[i].id.match(/\d+/), 10)] = min_price;
    }  
    json_sort = JSON.stringify({sort_id: sort_id, prices: prices});
  }
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { autoserv_sort: json_sort },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      console.log(data);
    }
  );
}*/