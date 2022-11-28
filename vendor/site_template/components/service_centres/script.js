

//обновление списка моделей в dropdown
function setServices(category) {
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