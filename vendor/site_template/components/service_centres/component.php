<?php
if (!isset($_SESSION['user']['id'])) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();


//получение списка услуг по id категории и передача в js
if (isset($_POST['category_id'])) {
    $category_id = json_decode($_POST['category_id']);
    if (!empty($category_id)) {
        $services = getServicesById($category_id);
        $json_services = json_encode($services);
        echo $json_services;
    } else {
        return NULL;
    }
}


//получение списка услуг по id категории и передача в js
if (isset($_POST['search_autoservices'])) {
    $search_parametres = json_decode($_POST['search_autoservices'], true);
    if (!empty($search_parametres)) {
        $autoservices_list = getAutoservicesByParameters($search_parametres);
        $autoserv_list_template = '<h3>Сервисные центры по вашему запросу</h3>
            <div class="autoservices_area">';
        if ($autoservices_list != NULL) {
            foreach ($autoservices_list as $autoservice) {
                foreach ($autoservice as &$value) {
                    if ($value == NULL) {
                        $value = '-';
                    }
                }
                $autoserv_list_template .= '<div class="plate" id="autoservice_id_' . $autoservice['id'] . '" onclick="getAutoserviceInfo(this)">                    
                        <h3>' . $autoservice['name'] . '</h3>
                        <div class="central">
                            <div class="text_list name">
                                <p>Cтоимость услуг (р)</p><p>Телефон</p><p>Адрес</p>
                            </div>
                            <div class="text_list value">
                                <p>' . $autoservice['price'] . '</p>
                                <p>' . $autoservice['phone'] . '</p>
                                <p>' . $autoservice['address'] . '</p>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            $autoserv_list_template .= '<p>По вашему запросу ничего не найдено</p>';
        }
        $autoserv_list_template .= '</div>';
        echo $autoserv_list_template;
    } else {
        return NULL;
    }
}


//получение информации о сервисном центре по id и передача в js
if (isset($_POST['autoserv_get_info'])) {
    $autoservice_id = json_decode($_POST['autoserv_get_info'], true);
    $autoservice_template = '<div class="panel show_autoservice" id="' . $autoservice_id . '">';
    if ($autoservice_id != NULL) {
        $autoservice_info = getAutoserviceInfoById($autoservice_id);
        foreach ($autoservice_info as &$value) {
            if ($value == NULL) {
                $value = '-';
            }
        }
        $autoservice_template .= '<h3>' . $autoservice_info['name'] . '</h3>

        <div class="autoservices_area with_btn">
            <p class="name">Описание</p><p class="value">' . $autoservice_info['text'] . '</p>
            <p class="name">Фотографии</p>';
        $ar_photos = getPhotosArray($autoservice_info['id']);
        if ($ar_photos == NULL) {
            $autoservice_template .= '<p id="photos_p" style="display: block">Отсутствуют</p>';
        } else {
            $autoservice_template .= '<div class="photos" style="display: block">';
            $autoservice_template .= '<img class="major_photo" id="photo_main" src="' . $ar_photos[0] . '" alt="' . substr($ar_photos[0], stripos($ar_photos[0], '-') + 1) . '">';
            for ($photo_number = 0; $photo_number < count($ar_photos); $photo_number++) {
                $autoservice_template .= '<img class="minor_photo" id="photo_' . $photo_number . '" src="' . $ar_photos[$photo_number] . '" 
                    alt="' . substr($ar_photos[$photo_number], stripos($ar_photos[$photo_number], '-') + 1) . '" onclick="gallery(this)">';
            }
            $autoservice_template .= '</div>';
        }
        $autoservice_template .= '<p class="name">Телефон</p><p class="value">' . $autoservice_info['phone'] . '</p>
            <p class="name">Адрес</p><p class="value">' . $autoservice_info['address'] . '</p>
            <div  class="mb-3">
                <label class="form-label name" for="autoserv_services">Обслуживаемые марки автомобилей</label>';
        if ($autoservice_info['brand_list'] != NULL) {
            $autoservice_template .= '<div class="central" id="autoservice_brands">';
            foreach ($autoservice_info['brand_list'] as $brand) {
                $autoservice_template .= '<p id="' . $brand['id'] . '">' . $brand['name'] . '</p>';
            }
            $autoservice_template .= '</div>';
        } else {
            $autoservice_template .= '<p id="autoservice_brands_p" style="display: none">Перечень марок не указан</p>';
        }
        $autoservice_template .= '</div>
            <div class="mb-3">
                <label class="form-label name" for="autoserv_services">Список услуг сервисного центра</label>';
        if (!empty($autoservice_info['services_id'])) {
            $autoservice_template .= '<select class="form-select" id="autoserv_services" name="autoserv_services" aria-label="Default select example" onchange="getServiceInfo(this)">';
            foreach ($autoservice_info['services_id'] as $service_id) {
                $autoservice_template .= '<option value="' . $service_id . '">' . getServiceNameById($service_id) . '</option>';
            }
            $service_info = getServiceInfo($autoservice_info['id'], $autoservice_info['services_id'][0]);
            foreach ($service_info as &$value) {
                if ($value == NULL) {
                    $value = '-';
                }
            }
            $autoservice_template .= '</select>
                <div class="central" id="service_info">
                    <label class="form-label name" for="autoserv_services">Категория</label>
                    <p>' . $service_info['category'] . '</p>
                    <label class="form-label name" for="autoserv_services">Стоимость</label>
                    <p>' . $service_info['price'] . '</p>
                    <label class="form-label name" for="autoserv_services">Сертификация</label>';
            if ($service_info['certification'] == '-') {
                $autoservice_template .= '<p style="display: block">Отсутствует</p>';
                $autoservice_template .= '<a target="_blank" href="' . $service_info['certification'] . '" style="display: none">' . mb_substr($service_info['certification'], 1 + strpos($service_info['certification'], '-')) . '</a>';
            } else {
                $autoservice_template .= '<p style="display: none">Отсутствует</p>';
                $autoservice_template .= '<a target="_blank" href="' . $service_info['certification'] . '" style="display: block">' . mb_substr($service_info['certification'], 1 + strpos($service_info['certification'], '-')) . '</a>';
            }
            $autoservice_template .= '<label class="form-label name" for="autoserv_services">Описание</label>
                        <p>' . $service_info['text'] . '</p>
                    </div>
                </div>';
        } else {
            $autoservice_template .= '<p id="autoservice_services_p" style="display: none">Перечень услуг не указан</p>';
        }
    } else {
        $autoservice_template .= 'Здесь мог бы быть Ваш автосервис';
    }
    $autoservice_template .= '</div>
    <div class="btn_div">
    <button onclick="showcomplaint(this)" value="' . $autoservice_info['id'] . '" id="show_complaint_' . $autoservice_info['id'] . '"name="show_complaint" class="btn btn-outline-danger" type="button">Пожаловаться</button>
        <button class="btn btn-primary" id="create_application" type="button" onclick="createApplication(this)">Сформировать заявку</button>
        </div>';
    echo $autoservice_template;
}


//получение информации об услуге по id 
if (isset($_POST['autoserv_service_id'])) {
    $data = json_decode($_POST['autoserv_service_id'], true);
    if (!empty($data)) {
        $services = getServiceInfo($data['autoservice_id'], $data['service_id']);
        foreach ($services as &$value) {
            if ($value == NULL) {
                $value = '-';
            }
        }
        $json_services = json_encode($services);
        echo $json_services;
    } else {
        return NULL;
    }
}


//формирование заявки 
if (isset($_POST['create_application'])) {
    $data = json_decode($_POST['create_application'], true);
    if (!empty($data)) {
        $autoservice_info = getAutoserviceInfoById($data['autoservice_id']);
        $client_info = getAllUserInfo($data['client_id']);
        $application_template = '<div class="panel" id="send_application">
            <h3>Заявка на обслуживание</h3>';
        if (getAutosById($client_info['client_id']) == NULL) {
            $application_template .= '<p>Невозможно сформировать заявку с пустым списком автомобилей. Добавьте автомобиль во вкладке "Мои авто"</p></div>';
        } else {
            $application_template .= '<div class="autoservices_area">
                <p class="name">Название сервисного центра</p><p class="value"><b>' . $autoservice_info['name'] . '</b></p>
                <p class="name">ФИО</p><p class="value">' . $client_info['name_client'] . '</p>
                <p class="name">Номер телефона</p><p class="value">' . $client_info['phone_client'] . '</p>
                <div class="mb-3">
                    <label class="form-label name" for="auto_application">Автомобиль</label>
                    <select class="form-select req" id="auto_application" name="auto_application" aria-label="Default select example" required>';
            if ($data['auto_id'] == '') {
                $application_template .= '<option value="" disabled selected>Выберите ваш автомобиль</option>';
                $autos = getAutosById($client_info['client_id']);
                foreach ($autos as $auto_id => $ar_auto_info) {
                    $application_template .= '<option value="' . $auto_id . '">' . $ar_auto_info['brand'] . ' ' . $ar_auto_info['model'] . '</option>';
                }
            } else {
                $autos = getAutosById($client_info['client_id']);
                foreach ($autos as $auto_id => $ar_auto_info) {
                    if ($auto_id == $data['auto_id']) {
                        $application_template .= '<option value="' . $auto_id . '" selected>' . $ar_auto_info['brand'] . ' ' . $ar_auto_info['model'] . '</option>';
                    } else {
                        $application_template .= '<option value="' . $auto_id . '">' . $ar_auto_info['brand'] . ' ' . $ar_auto_info['model'] . '</option>';
                    }
                }
            }
            $application_template .= '</select>
                </div>';
            if (!empty($autoservice_info['services_id'])) {
                $exists_services = array_intersect($data['services_id'], $autoservice_info['services_id']);
                $application_template .= '<div class="multiselect mb-3">
                    <label class="form-label" for="show_application_services">Услуги</label>
                    <div class="form-select selectBox" id="show_application_services" onclick="showCheckboxes(this)">
                        <option>Выбрано услуг: ' . count($exists_services) . '</option>
                    </div>
                    <div class="checkboxes" id="application_services">';
                foreach ($autoservice_info['services_id'] as $service_id) {
                    if (in_array($service_id, $exists_services)) {
                        $application_template .= '<label for="' . $service_id . '">
                                    <input type="checkbox" id="' . $service_id . '" onchange="setPrice(this)" checked/>' . getServiceNameById($service_id) . '</label>';
                    } else {
                        $application_template .= '<label for="' . $service_id . '">
                                    <input type="checkbox" id="' . $service_id . '" onchange="setPrice(this)"/>' . getServiceNameById($service_id) . '</label>';
                    }
                }
                $application_template .= '</div>
                    </div>';
            } else {
                $application_template .= '<p class="name">Услуги</p><p class="value">Сервисный центр не указал перечень услуг</p>';
            }
            $price = 0;
            foreach ($exists_services as $service_id) {
                $price += getServicePriceById($data['autoservice_id'], $service_id);
            }
            $application_template .= '<div>
                    <label class="form-label name" for="price" style="margin-bottom: 0">Стоимость</label>
                    <p class="value" id="price"><b>' . $price . ' р</b></p>
                </div>
                <div class="mb-3">
                    <label class="form-label name" for="desired_date">Желаемая дата</label>
                    <input class="form-control" id="desired_date" name="desired_date" type="date" min="' . date('Y-m-d') . '" placeholder="Желаемая дата обслуживания"/>
                </div>
                <div class="mb-3">
                    <label class="form-label name" for="desired_time">Желаемая время</label>
                    <input class="form-control" id="desired_time" name="desired_time" type="time" value="12:00" placeholder="Желаемое время записи на обслуживание"/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="comment">Комментарий</label>
                    <textarea class="form-control" id="comment" name="comment" type="textarea" placeholder="Комментарий к заявке"></textarea>
                </div>
            </div>
            <div class="btn_div">
                <button class="btn btn-primary" id="send_application" type="button" onclick="sendApplication(this)">Отправить</button>
                <button class="btn btn-secondary" id="cancel_application" type="button" onclick="cancelApplication()">Отменить</button>
            </div>
            </div>';
        }
        echo $application_template;
    } else {
        return NULL;
    }
}


//получение списка услуг по id категории и передача в js
if (isset($_POST['send_application'])) {
    $data = json_decode($_POST['send_application'], true);
    if (!empty($data)) {
        foreach ($data as &$value) {
            if ($value == '') {
                $value = NULL;
            }
        }
        $services_id = '{' . implode(',', $data['services_id']) . '}';
        $sql_create_application = "INSERT INTO public.application(client_id, auto_id, autoservice_id, date, autoserv_serv_id, price, 
            text, status, date_payment) VALUES (:client_id, :auto_id, :autoservice_id, :date, :autoserv_serv_id, :price, :text, :status, :date_payment)";
        $stmt = $pdo->prepare($sql_create_application);
        $stmt->execute([
            'client_id' => $data['client_id'],
            'auto_id' => $data['auto_id'],
            'autoservice_id' => $data['autoservice_id'],
            'date' => $data['date'],
            'autoserv_serv_id' => $services_id,
            'price' => $data['price'],
            'text' => $data['comment'],
            'status' => "Ожидает подтверждения",
            'date_payment' => NULL
        ]);
    } else {
        return NULL;
    }
}


//получение списка услуг по id категории и передача в js
if (isset($_POST['get_price'])) {
    $data = json_decode($_POST['get_price'], true);
    if (!empty($data)) {
        $price = getServicePriceById($data['autoservice_id'], $data['service_id']);
        echo $price;
    } else {
        return NULL;
    }
}

if (isset($_POST['autoservice_id']) and isset($_POST['show_complaint'])) {
    $autoservice_id = $_POST['autoservice_id'];
    $sql = "SELECT name_autoservice FROM public.autoservice WHERE autoservice_id=" . $autoservice_id;
    $autoservice = $pdo->query($sql)->fetch();
    $sql_check_complaint = "SELECT complainant_id FROM public.complaint WHERE complainant_id=" . $_SESSION['user']['id']
        . " AND inspected_user_id=" . $autoservice_id;
    $result = $pdo->query($sql_check_complaint)->fetch();
    if ($result) {
        $text_modal = '<p>Вы уже отправляли жалобу на ' . $autoservice['name_autoservice'] . ' Администратор обязательно проверит ее</p>';
        $button_accept = '<button type="button" data-bs-dismiss="modal" class="btn btn-primary">Понятно</button>';
    } else {
        $text_modal = '
        <p>Опишите причину жалобы в поле ниже. Спасибо, что делаете мир лучше!</p>
        
        <div class="form-floating" name="complaint_text">
              <textarea class="form-control" placeholder="Причина жалобы" id="complaint_' . $autoservice_id . '" style="height: 100px"></textarea>
              <label for="complaint_' . $autoservice_id . '">Причина жалобы</label>
        </div>';
        $button_accept = '<button onclick="sendcomplaint(this)" type="button" data-bs-dismiss="modal" value="' . $autoservice_id . '" class="btn btn-primary">Отправить жалобу</button>';
    }


    echo '<div class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Оформление жалобы на ' . $autoservice['name_autoservice'] . '</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        ' . $text_modal . '       

        </div>
        <div class="modal-footer">
        ' . $button_accept . '
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>';
}
if (isset($_POST['autoservice_id']) and isset($_POST['text_complaint'])) {
    send_complaint($_POST['autoservice_id'], $_POST['text_complaint'], $_SESSION['user']['id']);
}




//получение списка отсортированных СЦ
/*f (isset($_POST['autoserv_sort'])) {
    $autoserv_sort = json_decode($_POST['autoserv_sort']);
    $template++;
    echo $template;
    if ($autoserv_sort['sort_id'] == 0) {
        
    } else if ($autoserv_sort['sort_id'] == 1) {
        asort($autoserv_sort['prices']);
    } else {
        arsort($autoserv_sort['prices']);
    }
}*/