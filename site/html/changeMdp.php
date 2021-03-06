<?php

    session_start();
    $user = "";
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } else {
        $user = $_SESSION['userLogin'];
    }
    include_once 'utils.php';
    if(isset($_POST['sbmt-edit'])) {
        $csrf_token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);
        if(!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
            header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
            exit;
        }
        
        if(isset($_POST['inputPassword']) && isset($_POST['inputPassword2']) && isset($_POST['formerPassword'])) {
            // Vérifier que le mot de passe fourni est correct
            include 'dbConnect.php';
            $sth = $file_db->prepare('SELECT * FROM user WHERE login = ?');
            $sth->execute(array($user));
            $data = $sth->fetch();

            if(hash('sha256', $_POST['formerPassword']) == $data["password"]) {
                $password = $_POST['inputPassword'];
                if ($password == $_POST['inputPassword2']) {
                    // Vérifier que le mot de passe contient au moins 8 char et un chiffre
                    if(passwordPolicy($_POST['inputPassword'])) {

                        $sth = $file_db->prepare('UPDATE user SET password = ? WHERE login = ?');
                        $sth->execute(array(hash('sha256', $password), $user));
                        header('Location: index.php');
                    } else {
                        echo "<script type='text/javascript'>alert('Password must be min 8 caracters length, contain a number and an upper case');</script>";
                    }
                } else {
                    echo "<script type='text/javascript'>alert('Les mots de passe ne correspondent pas');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Login failed');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('All parameters must be filled!');</script>";
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

    <label for="formerPassword" class="sr-only">Ancien mot de passe</label>
    <input type="password" id="formerPassword" name="formerPassword" class="form-control" placeholder="Password" required autofocus>

    <label for="inputPassword" class="sr-only">Nouveau mot de passe</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>

    <label for="inputPassword2" class="sr-only">Confirmer le nouveau mot de passe</label>
    <input type="password" id="inputPassword2" name="inputPassword2" class="form-control" placeholder="Password" required >

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?: '' ?>" />
    <input type="submit" name="sbmt-edit" class="btn btn-lg btn-primary btn-block" value="Valider">
</form>


</body>
</html>