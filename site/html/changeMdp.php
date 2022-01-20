<?php

    session_start();
    $user = "";
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } else {
        $user = $_SESSION['userLogin'];
    }

    if(isset($_POST['inputPassword'])) {
        $password = $_POST['inputPassword'];
        if ($password != $_POST['inputPassword2']) {
            echo "<script type='text/javascript'>alert('Les mots de passe ne correspondent pas');</script>";

        } else {    // Changement du mdp
            include 'dbConnect.php';

            $sth = $file_db->prepare('UPDATE user SET password = ? WHERE login = ?');
            $sth->execute(array($password, $user));
            header('Location: index.php');
        }
    }

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Changer mot de passe</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" method="POST">
    <h1 class="h3 mb-3 font-weight-normal">Changer le mot de passe</h1>

    <label for="inputPassword" class="sr-only">Nouveau mot de passe</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required autofocus>

    <label for="inputPassword2" class="sr-only">Confirmer le nouveau mot de passe</label>
    <input type="password" id="inputPassword2" name="inputPassword2" class="form-control" placeholder="Password" required >

    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Valider">
</form>


</body>
</html>