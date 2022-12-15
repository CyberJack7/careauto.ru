
//отображение истории обслуживания автомобиля
function showAutoServHistory(auto) {
    cancelAddHistoryRecord();
    let auto_id = auto.id;
    document.getElementsByClassName("item_active")[0].className = "list-group-item list-group-item-action";
    auto.className = 'list-group-item list-group-item-action item_active';
    let client_id = document.getElementsByClassName("central")[0].id;
    let json_data = JSON.stringify({'client_id': client_id, 'auto_id': auto_id});
    $.post("/vendor/site_template/components/cars_serv_history/component.php", {show_applications: json_data}, 
        function(data){
            document.getElementById("show_applications").innerHTML = '';
            document.getElementById("show_applications").insertAdjacentHTML('beforeend', data);
    });
}


//изменение статуса конфиденциальности записи
function setConfidentiality(checkbox) {
    let history_id = parseInt(checkbox.id.match(/\d+/), 10);
    let confidentiality = 'false';
    if (checkbox.checked == true) {
        confidentiality = 'true';
    }
    let json_data = JSON.stringify({'history_id': history_id, 'confidentiality': confidentiality});
    $.post("/vendor/site_template/components/cars_serv_history/component.php", {confid_history: json_data});
}


//удаление записи об обслуживании
function deleteHistoryRecord(button) {
    let history_id = parseInt(button.id.match(/\d+/), 10);
    let confirmation = confirm("Вы уверены, что хотите удалить запись об обслуживании: " + document.getElementById("history_id_" + history_id).getElementsByTagName("h3")[0].innerHTML + "?");
    if (confirmation) {
        document.getElementById("history_id_" + history_id).remove();
        if (document.getElementById("history_area").getElementsByClassName("plate").length == 0) {
            document.getElementById("history_area").remove();
            document.getElementById("show_applications").getElementsByTagName("h3")[0].remove();
            document.getElementById("show_applications").insertAdjacentHTML('beforeend', '<h3>Иcтория обслуживания пуста</h3><div class="plate"><p>Для данного автомобиля не зафиксированно никаких записей в истории обслуживания</p></div><button class="btn btn-primary" id="add_history_record" type="button" onclick="addHistoryRecord(this)">Добавить новую запись</button>');
        }
        let json_history_id = JSON.stringify(history_id);
        $.post("/vendor/site_template/components/cars_serv_history/component.php", {delete_history: json_history_id});
    }  
}


//окно добавления записи об обслуживании
function addHistoryRecord() {
    document.getElementById("show_applications").style.display = 'none';
    $.post("/vendor/site_template/components/cars_serv_history/component.php", {add_history: ''}, 
    function(data){
        document.getElementsByClassName("central")[0].insertAdjacentHTML('beforeend', data);
    });
}


//создание записи об обслуживании
function createHistoryRecord() {
    if (document.getElementById("date").checkValidity() && document.getElementById("price").checkValidity()) {
        let array_create_history = {};
        array_create_history['client_id'] = document.getElementsByClassName("central")[0].id;
        array_create_history['auto_id'] = document.getElementsByClassName("item_active")[0].id;
        array_create_history['name_autoservice'] = document.getElementById("name_autoservice").value;
        array_create_history['date'] = document.getElementById("date").value;
        array_create_history['price'] = document.getElementById("price").value;
        array_create_history['text'] = document.getElementById("text").value;
        let services = [];
        let services_inputs = document.getElementById("services").getElementsByTagName("input");
        let amount_inputs
        for (let i = 0; i < amount_inputs; i++) {
            if (services_inputs[i].checked == true) {
                services.push(parseInt(services_inputs[i].id.match(/\d+/), 10));
            }
        }
        array_create_history['services'] = services;
    
        let json_data = JSON.stringify(array_create_history);
        $.post("/vendor/site_template/components/cars_serv_history/component.php", {create_history_record: json_data},
        function(data){
            cancelAddHistoryRecord();
            showAutoServHistory(document.getElementsByClassName("item_active")[0]);
        });
    }
}


//отменить добавление записи об обслуживании
function cancelAddHistoryRecord() {
    if (document.getElementById("add_history")) {
        document.getElementById("add_history").remove();
        document.getElementById("show_applications").style.display = 'block';
    }
}

function validInput(input) {
    if (input.value != "") {
        input.className = "form-control req";        
    } else {
        input.className = "form-control";
    }
}