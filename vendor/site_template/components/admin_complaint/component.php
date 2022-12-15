<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/lib/defines.php';
require_once PATH_CONNECT;
require_once PATH_SEND_EMAIL;
require_once PATH_QUERIES;
if (!isset($_SESSION['user']['id'])) {
    session_start();
}


function complaintCancel($complaint_id)
{
    $pdo = conn();
    $sql_del = "DELETE FROM public.complaint WHERE complaint_id=" . $complaint_id;
    $result = $pdo->exec($sql_del);
}

function complaintAccept($complaint_id, $text)
{
    $pdo = conn();
    $sql = "SELECT inspected_user_id FROM public.complaint WHERE complaint_id=" . $complaint_id;
    $ban_id = $pdo->query($sql)->fetch();

    return sendToBan($ban_id['inspected_user_id'], $_SESSION['user']['id'], $text);
}


if (!empty($_POST['complaint_id'])) {
    if ($_POST['status'] == 'accept') {
        complaintAccept($_POST['complaint_id'], $_POST['text']);
    } else {
        complaintCancel($_POST['complaint_id']);
    }
}