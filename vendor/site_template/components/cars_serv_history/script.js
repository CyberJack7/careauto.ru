
//обновление количества выбранных услуг
function showAutoServHistory(auto) {
    let auto_id = auto.id;
    let client_id = document.getElementsByClassName("central")[0].id;
    let json_data = JSON.stringify({'client_id': client_id, 'auto_id': auto_id});
    $.post("/vendor/site_template/components/cars_serv_history/component.php", {show_applications: json_data}, 
        function(data){
            console.log(data);
            let show_applications_area = document.getElementById("show_applications").getElementsByTagName("div");
            for (let i = 0; i < show_applications_area.length; i++){
                show_applications_area[0].remove();
            }
            document.getElementById("show_applications").insertAdjacentHTML('beforeend', data);
    });
}