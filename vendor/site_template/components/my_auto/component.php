<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_QUERIES;

$pdo = conn();

//получение моделей авто в соответствии с выбранной маркой из БД и отправка в JS
if (isset($_POST['brand_id'])) {
    $brand_id = json_decode($_POST['brand_id']);
    if (!empty($brand_id)) {
        $models = getModelById($brand_id);
        $json_models = json_encode($models);
        echo $json_models;
    } else {
        return null;
    }
}

//получение информации об авто по id из БД и отправка в JS
if (isset($_POST['auto_id'])) {
    $auto_id = json_decode($_POST['auto_id']);
    if (!empty($auto_id)) {
        $auto = getAutoInfoById($auto_id);
        $auto['brand'] = getBrandNameById($auto['brand_id']);
        $auto['model'] = getModelNameById($auto['model_id']);
        $auto['body'] = getBodyNameById($auto['body_id']);
        $auto['engine'] = getEngineNameById($auto['engine_id']) . '/' . $auto['engine_volume'] . 'л/' . $auto['engine_power'] . 'л.с.';
        $auto['gearbox'] = getGearboxNameById($auto['gearbox_id']);
        $auto['drive'] = getDriveNameById($auto['drive_id']);
        unset($auto['brand_id'], $auto['model_id'], $auto['body_id'], $auto['engine_id'], 
            $auto['engine_volume'], $auto['engine_power'], $auto['gearbox_id'], $auto['drive_id']);
        
        $json_auto = json_encode($auto);
        echo $json_auto;
    } else {
        return null;
    }
}

//добавление комплекта резины соответствующему автомобилю
if (isset($_POST['tires'])) {
    $tires = json_decode($_POST['tires']);
    for ($i = 0; $i < count($tires); $i++) {
        if ($tires[$i] == "") {
            $tires[$i] = null;
        }
    }
    if (!empty($tires)) {
        $sql_create_tires = "INSERT INTO public.tires(brand_tires, marking, tire_type_id, date_buy) VALUES (:brand_tires, :marking, :tire_type_id, :date_buy)";
        $stmt = $pdo->prepare($sql_create_tires);
        $stmt->execute([
            'brand_tires' => $tires[0],
            'marking' => $tires[2],
            'tire_type_id' => $tires[1],
            'date_buy' => $tires[3]
        ]);

        $tires_id = $pdo->lastInsertId(); //id созданного комплекта резины

        $sql_get_tires = "SELECT tires_id FROM public.automobile WHERE auto_id = " . $tires[4];
        $str_tires_id = $pdo->query($sql_get_tires)->fetch()['tires_id'];
        if ($str_tires_id != null) {
            $str_tires_id = substr($str_tires_id, 0, -1) . ',' . $tires_id . substr($str_tires_id, -1);
            $sql_tires_id = $pdo->quote($str_tires_id);
        } else {
            $str_tires_id = '{' . $tires_id . '}';
            $sql_tires_id = $pdo->quote($str_tires_id);
        }

        $sql_add_tires = "UPDATE public.automobile SET tires_id = " . $sql_tires_id . " WHERE auto_id = " . $tires[4];
        $stmt = $pdo->exec($sql_add_tires);
        $json_tires_id = json_encode($tires_id);
        echo $json_tires_id;
    }
}

//удаление комплекта резины у соответствующего автомобиля
if (isset($_POST['del_tires'])) {
    //удаление комплекта из tires_id автомобиля
    $del_tires = json_decode($_POST['del_tires'], true);
    var_dump($del_tires['auto_id']);
    $sql_get_tires = "SELECT tires_id FROM public.automobile WHERE auto_id = " . $del_tires['auto_id'];
    $tires_id = $pdo->query($sql_get_tires)->fetch()['tires_id'];

    $str_tires_id = str_replace(['{', '}'], '', $tires_id); //готовим строку к превращению в массив
    $ar_tires_id = explode(',', $str_tires_id); //превращаем строку в массив
    unset($ar_tires_id[array_search($del_tires['tires_id'], $ar_tires_id)]); //удаляем комплект из массива
    var_dump($ar_tires_id);
    if (!empty($ar_tires_id)) {
        $sql_tires_id = "{" . implode(",", $ar_tires_id) . "}"; //превращаем массив в строку
        $sql_quote_tires_id = $pdo->quote($sql_tires_id);
        $sql_del_tires = "UPDATE public.automobile SET tires_id = " . $sql_quote_tires_id . " WHERE auto_id = " . $del_tires['auto_id'];
    } else {
        $sql_del_tires = "UPDATE public.automobile SET tires_id = null WHERE auto_id = " . $del_tires['auto_id'];
    }
    $stmt = $pdo->exec($sql_del_tires);

    //удаление самого комплекта из таблицы БД tires
    $sql_tires_delete = "DELETE FROM public.tires WHERE tires_id = " . $del_tires['tires_id'];
    $stmt = $pdo->exec($sql_tires_delete);
}