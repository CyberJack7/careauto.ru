<?php

//установить свои имя пользователя, пароль, название БД и имена полей
$driver = 'pgsql';
$host = 'localhost';
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'Jack';
$port='5432';
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO("$driver:host=$host;port=$port;dbname=$db_name;user=$db_user;password=$db_pass");
    echo "PDO connection object created<br>";
}
catch(PDOException $e){
    echo $e->getMessage();
}

$result = $pdo->query('SELECT * FROM public.services');
echo '<b> Доступные услуги:<br> </b>';
foreach ($result as $row){
    echo $row['name'] . " от " . $row['price'] . ' рублей <br>';
} 

?>