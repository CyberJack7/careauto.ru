
//обновление количества выбранных услуг
function deleteApplication(del_btn) {
  let application_id = parseInt(del_btn.id.match(/\d+/), 10);
  let confirmation = confirm("Вы уверены, что хотите отменить заявку " + document.getElementById("application_id_" + application_id).getElementsByTagName("h3")[0].innerHTML + "?");
  if (confirmation) {
    document.getElementById("application_id_" + application_id).remove();
    let json_application_id = JSON.stringify(application_id);    
    $.post("/vendor/site_template/components/client_applications/component.php", {delete_application: json_application_id});
  }  
}
