<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';

class Main {
    //подключение компонента
    public static function includeComponent($component_name) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/components/' . $component_name . '/template.php';
    }
}