$("document").ready(function () {
  let btn = document.querySelector("#del_btn");
  btn.onclick = function () {
    let confirmation = confirm(
      "Вы уверены, что хотите удалить все данные вместе с аккаунтом?"
    );
    if (confirmation) {
      let req = new XMLHttpRequest();
      req.open("POST", "account_delete.php"); //файл удаления аккаунта
      req.send(null);
      location.href = "/"; //переход на главную страницу
    }
  };

  let reset_requisites_btn = document.querySelector("#reset_requisites");
  reset_requisites_btn.onclick = function () {
    let req = new XMLHttpRequest();
    req.open(
      "POST",
      "/vendor/site_template/components/profile/delete_info.php"
    ); //файл удаления реквизитов аккаунта
    req.send(null);
    location.href = "/profile/"; //переход на страницу профиля
  };

  let load_photos = document.querySelector("#photos");
  load_photos.onchange = function () {
    let amount = document
      .getElementsByClassName("photos")[0]
      .getElementsByClassName("minor_photo").length;
    if (amount == null) {
      amount = 0;
    }
    if (load_photos.files.length > 5 - amount) {
      alert("Можно загрузить до 5 фотографий");
      load_photos.value = "";
    }
  };

  let del_all_photos = document.querySelector("#del_all_photos"); //удаление всех фото
  if (del_all_photos) {
    del_all_photos.onclick = function () {
      let confirmation = confirm(
        "Вы уверены, что хотите удалить все фотографии?"
      );
      if (confirmation) {
        let photos = document.getElementsByClassName("photos")[0];
        photos.remove();
        let btns = document.getElementsByClassName("btn_div")[0];
        btns.remove();
        let ar_src_photos = [];
        let src_photos = JSON.stringify(ar_src_photos);
        data_send = { dataQuery: src_photos };
        getAjax(
          "/vendor/site_template/components/profile/delete_info.php",
          data_send
        );
      }
    };
  }

  let del_photo = document.querySelector("#del_photo"); //удаление одного фото
  if (del_photo) {
    del_photo.onclick = function () {
      let amount = document
        .getElementsByClassName("photos")[0]
        .getElementsByTagName("img").length;
      let ar_src_photos = [];
      if (amount - 1 > 0) {
        if (amount - 1 == 1) {
          let photos = document.getElementsByClassName("photos")[0];
          photos.remove();
          let btns = document.getElementsByClassName("btn_div")[0];
          btns.remove();
        } else {
          let major_photo = document.getElementsByClassName("major_photo")[0];
          major_photo_src = major_photo.src;
          count = 0;
          for (let i = 0; i < amount - 1; i++) {
            let minor_photo = document.getElementById("photo_" + i);
            if (minor_photo.src == major_photo_src) {
              minor_photo.remove();
            } else {
              minor_photo.id = "photo_" + count;
              ar_src_photos[count] = minor_photo.src;
              count++;
            }
          }
          major_photo.src = document.getElementById("photo_0").src;
        }
      }
      let src_photos = JSON.stringify(ar_src_photos);
      data_send = { dataQuery: src_photos };
      getAjax(
        "/vendor/site_template/components/profile/delete_info.php",
        data_send
      );
    };
  }
});

function set_brand(cb) {
  $data_send = { brand: cb.id };
  let title = document
    .getElementsByClassName("form-select selectBox")[0]
    .getElementsByTagName("option")[0];
  let checkboxes = document
    .getElementsByClassName("checkboxes")[0]
    .getElementsByTagName("input");
  len_cb = checkboxes.length;
  amount = 0;
  for (let i = 0; i < len_cb; i++) {
    if (checkboxes[i].checked) {
      amount++;
    }
  }
  title.innerHTML = "Выбрано марок авто: " + amount;
  if (cb.checked) {
    getAjax(
      "/vendor/site_template/components/profile/change_main_data.php",
      $data_send
    );
  } else {
    getAjax(
      "/vendor/site_template/components/profile/delete_info.php",
      $data_send
    );
  }
}

function validInput(input) {
  if (input.value != "") {
      input.className = "form-control req";        
  } else {
      input.className = "form-control";
  }
}