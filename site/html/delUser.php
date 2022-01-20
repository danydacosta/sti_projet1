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

    $file_db = dbConnection();
    $sth = $file_db->prepare('DELETE FROM user WHERE login = ?');
    $sth->execute(array($_GET['login']));
    
    //$file_db->exec("DELETE FROM user WHERE login = '{$_GET['login']}'");
    header('Location: index.php');

?>
