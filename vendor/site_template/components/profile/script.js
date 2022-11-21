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
      .getElementsByTagName("img").length;
    if (load_photos.files.length > 5 - (amount - 1)) {
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
      console.log(src_photos);
      data_send = { dataQuery: src_photos };
      getAjax(
        "/vendor/site_template/components/profile/delete_info.php",
        data_send
      );
    };
  }
});
function getAjax(url, data_send) {
  $.ajax({
    url: url,
    method: "post",
    dataType: "html",
    data: data_send,
    success: function (data) {
      /* функция которая будет выполнена после успешного запроса.  */
      console.log(data); /* В переменной data содержится ответ от index.php. */
    },
  });
}

function gallery(photo) {
  let major_photo = document.getElementsByClassName("major_photo")[0];
  major_photo.src = photo.src;
}

function set_brand(cb) {
  $data_send = { brand: cb.id };
  let title = document
    .getElementsByClassName("form-select selectBox")[0]
    .getElementsByTagName("option")[0];
  let checkboxes = document
    .getElementById("checkboxes")
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

var expanded = true;

function showCheckboxes() {
  let checkboxes = document.getElementById("checkboxes");
  if (expanded) {
    checkboxes.style.display = "block";
    expanded = false;
  } else {
    checkboxes.style.display = "none";
    expanded = true;
  }
}
