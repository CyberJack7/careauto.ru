<?php

function conn() {
    //установить свои имя пользователя, пароль, название БД и имена полей
    $driver = 'pgsql';
    $host = 'localhost';
    $db_name = 'postgres';
    $db_user = 'postgres';
    $db_pass = 'pass';
    $port = '5432';
    $charset = 'utf8';
    $dsn = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $db_name . ';';
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
	// make a database connection
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        // $pdo = new PDO("$driver:host=$host;port=$port;dbname=$db_name;user=$db_user;password=$db_pass");
        //echo "PDO connection object created<br />";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return ($pdo ? $pdo : false) ;
}