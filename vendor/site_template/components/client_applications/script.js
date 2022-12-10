
//отмена заявки
function deleteApplication(del_btn) {
  let application_id = parseInt(del_btn.id.match(/\d+/), 10);
  let confirmation = confirm("Вы уверены, что хотите отменить заявку " + document.getElementById("application_id_" + application_id).getElementsByTagName("h3")[0].innerHTML + "?");
  if (confirmation) {
    document.getElementById("application_id_" + application_id).remove();
    let json_application_id = JSON.stringify(application_id);    
    $.post("/vendor/site_template/components/client_applications/component.php", {delete_application: json_application_id});
  }  
}


//перейти к оплате заявки
function payApplication(pay_btn) {
  let application_id = parseInt(pay_btn.id.match(/\d+/), 10);
  let client_id = parseInt(document.getElementsByTagName("h1")[0].id.match(/\d+/), 10);
  let json_application_id = JSON.stringify({'client_id': client_id, 'application_id': application_id});
  $.post("/vendor/site_template/components/client_applications/component.php", {pay_application: json_application_id}, 
  function(data){
    document.getElementsByClassName("column")[0].style.display = 'none';
    document.getElementsByTagName("h1")[0].style.display = 'none';
    document.getElementsByClassName("column")[0].insertAdjacentHTML('afterend', data);
    $("#card_number").mask("9999-9999-9999-9999");
    $("#validity_date").mask("99/99");
    $("#cvc_cvv").mask("999", {placeholder: "" });
  });
}


//отменить заявку
function cancelPayApplication() {
  document.getElementsByClassName("content")[0].remove();
  document.getElementsByClassName("column")[0].style.display = 'block';
  document.getElementsByTagName("h1")[0].style.display = 'block';
}


//оплатить заявку
function submitPayApplication(pay_btn) {
  let application_id = parseInt(pay_btn.id.match(/\d+/), 10);
  let client_id = parseInt(document.getElementsByClassName("panel")[0].id.match(/\d+/), 10);
  let card_number = document.getElementById("card_number").value.replace(/[^0-9]/g, '');
  let validity_date = document.getElementById("validity_date").value.replace('/', '');
  let cardholder_name = document.getElementById("cardholder_name").value.toLowerCase();
  let cvc_cvv = document.getElementById("cvc_cvv").value;
  let json_application_pay = JSON.stringify({
    'client_id': client_id, 
    'application_id': application_id,
    'card_number': card_number,
    'validity_date': validity_date,
    'cardholder_name': cardholder_name,
    'cvc_cvv': cvc_cvv
  });
  $.post("/vendor/site_template/components/client_applications/component.php", {submit_pay_application: json_application_pay}, 
  function(data){
    let valid = JSON.parse(data);
    if (document.getElementsByClassName("alert")[0]) {
      document.getElementsByClassName("alert")[0].remove();
    }
    if (valid == 0) {
      document.getElementsByClassName("panel")[0].insertAdjacentHTML('afterend', '<div class="alert alert-success" role="alert" style="max-width: 500px">Оплата прошла успешно. Через 5 секунд страница обновится</div>');
      setTimeout(function(){location.reload()}, 5 * 1000);
    } else if (valid == 1){
      document.getElementsByClassName("panel")[0].insertAdjacentHTML('afterend', '<div class="alert alert-warning" role="alert" style="max-width: 500px">Данные карты заполнены неверно. Попробуйте снова</div>');
    } else if (valid == 2){
      document.getElementsByClassName("panel")[0].insertAdjacentHTML('afterend', '<div class="alert alert-warning" role="alert" style="max-width: 500px">На карте недостаточно средств. Попробуйте снова</div>');
    } else if (valid == 3){
      document.getElementsByClassName("panel")[0].insertAdjacentHTML('afterend', '<div class="alert alert-warning" role="alert" style="max-width: 500px">Карты не существует. Попробуйте снова</div>');
    }
  });
}