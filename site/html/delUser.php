<?php

    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    include_once 'utils.php';

    $user = $_SESSION['userLogin'];

    if (!checkAdmin($user)) {
        header('Location: index.php');
    }

    $csrf_token = filter_input(INPUT_GET, 'csrf_token', FILTER_SANITIZE_STRING);
    if(!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
        exit;
    }

    $file_db = dbConnection();
    $sth = $file_db->prepare('DELETE FROM user WHERE login = ?');
    $sth->execute(array($_GET['login']));
    
    header('Location: index.php');

?>
