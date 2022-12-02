
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


//получение информации о сервисном центре
function getAutoserviceInfo(plate) {
  let autoservice_id = parseInt(plate.id.match(/\d+/), 10); //id выбранного СЦ
  let json_autoservice_id = JSON.stringify(autoservice_id);
  $.post(
    "/vendor/site_template/components/service_centres/component.php",
    { autoserv_get_info: json_autoservice_id },
    function (data) {
      //функция которая будет выполнена после успешного запроса
      let ar_autoservice_info = JSON.parse(data); //конвертировали JSON string из data в js object
      
      //вывод основной текстовой информации
      let show_autoservice_area = document.getElementsByClassName("show_autoservice")[0];
      show_autoservice_area.id = ar_autoservice_info['id'];
      show_autoservice_area.getElementsByTagName("h3")[0].innerHTML = ar_autoservice_info['name'];
      let value_p = show_autoservice_area.getElementsByClassName("value");
      value_p[0].innerHTML = ar_autoservice_info['text'];
      value_p[1].innerHTML = ar_autoservice_info['phone'];
      value_p[2].innerHTML = ar_autoservice_info['address'];
      //вывод фотографий СЦ
      let autoservice_photos = document.getElementsByClassName("photos")[0]; //внутренняя область вывода фотографий
      let autoservice_photos_p = document.getElementById("photos_p");
      let amount_photos = ar_autoservice_info['photos'].length; //количество добавляемых фотографий
      if (amount_photos == 0) {
        autoservice_photos.style.display = 'none';
        autoservice_photos_p.style.display = 'block';
      } else {
        autoservice_photos_p.style.display = 'none';
        autoservice_photos.style.display = 'block';
        let major_photo = autoservice_photos.getElementsByClassName("major_photo")[0];
        major_photo.src = ar_autoservice_info['photos'][0]['src'];
        major_photo.alt = ar_autoservice_info['photos'][0]['name'];
        let minor_photos = autoservice_photos.getElementsByClassName("minor_photo");
        minor_photos[0].src = ar_autoservice_info['photos'][0]['src'];
        minor_photos[0].alt = ar_autoservice_info['photos'][0]['name'];
        let i = 1;
        for (; i < amount_photos; i++) {
          minor_photos[i].src = ar_autoservice_info['photos'][i]['src'];
          minor_photos[i].alt = ar_autoservice_info['photos'][i]['name'];
          minor_photos[i].removeAttribute("style");
        }
        let amount_minor_photos = minor_photos.length;
        while (i < amount_minor_photos) {
          minor_photos[i].style.display = "none";
          i++;
        }
      }
      //вывод списка обслуживаемых марок
      let autoservice_brands = document.getElementById("autoservice_brands"); //внутренняя область вывода марок
      let autoservice_brands_p = document.getElementById("autoservice_brands_p");
      let amount_brands = ar_autoservice_info['brand_list'].length; //количество добавляемых марок
      if (amount_brands == 0) {
        autoservice_brands.style.display = 'none';
        autoservice_brands_p.style.display = 'block';
      } else {
        autoservice_brands_p.style.display = 'none';
        autoservice_brands.style.display = 'block';
        let ar_brands_p = autoservice_brands.getElementsByTagName("p");
        let amount_p = ar_brands_p.length; //количество текущих марок
        let i = 0;
        for (; i < amount_brands && i < amount_p; i++) {
          ar_brands_p[i].id = ar_autoservice_info['brand_list'][i]['id'];
          ar_brands_p[i].innerHTML = ar_autoservice_info['brand_list'][i]['name'];
        }
        if (i < amount_brands) {
          for (; i < amount_brands; i++) {
            let p_brand = document.createElement("p");
            p_brand.id = ar_autoservice_info['brand_list'][i]['id'];
            p_brand.innerHTML = ar_autoservice_info['brand_list'][i]['name'];
            autoservice_brands.appendChild(p_brand);
          }
        } else if (i < amount_p) {
          while (i < amount_p) {
            ar_brands_p[i].remove();
            amount_p--;
          }          
        }
      }
      //вывод списка услуг СЦ
      let autoservice_services = document.getElementById("autoserv_services"); //перечень услуг автосервиса
      let autoservice_service_info = document.getElementById("service_info"); //вывод подробной инфы об услуге
      let autoservice_services_p = document.getElementById("autoservice_services_p"); //текст если 0 услуг
      let amount_service = ar_autoservice_info['services'].length; //количество добавляемых услуг
      if (amount_service == 0) {
        autoservice_services.style.display = 'none';
        autoservice_service_info.style.display = 'none';
        autoservice_services_p.style.display = 'block';
      } else {
        autoservice_brands_p.style.display = 'none';
        autoservice_brands.style.display = 'block';
        autoservice_service_info.style.display = 'block';
        let autoservice_service_option = autoservice_services.getElementsByTagName("option");
        let amount_option = autoservice_service_option.length; //количество текущих марок
        let i = 0;
        for (; i < amount_service && i < amount_option; i++) {
          autoservice_service_option[i].value = ar_autoservice_info['services'][i]['id'];
          autoservice_service_option[i].innerHTML = ar_autoservice_info['services'][i]['name'];
        }
        if (i < amount_service) {          
          for (; i < amount_service; i++) {
            autoservice_services.append(new Option(ar_autoservice_info['services'][i]['name'], ar_autoservice_info['services'][i]['id']));
          }
        } else if (i < amount_option) {
          while (i < amount_option) {
            autoservice_service_option[i].remove();
            amount_option--;
          }
        }
      }
    }
  );
}


//отображение информации об услуге
function setServiceInfo(ar_service_info) {
  console.log(ar_service_info);
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
  console.log(json_data);
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