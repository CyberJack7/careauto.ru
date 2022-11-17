<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;

//название города по id
function doc_path($user_id){
  $pdo = conn();
  $sql = "SELECT document FROM Public.autoservice WHERE autoservice_id = " . $user_id;
  $doc_name = $pdo->query($sql)->fetch()['document'];
  return $doc_name;
}