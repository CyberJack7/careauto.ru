<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['auth'])) {
    $_SESSION['not_auth_user']['attempt'] = 3;
}