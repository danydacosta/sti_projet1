<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    include_once 'utils.php';

    if (!checkAdmin($_SESSION['userLogin'])) {
        header('Location: index.php');
    }

    if (isset($_POST['login'])) {
        $file_db = dbConnection();
        $file_db->exec("INSERT INTO user (login, nom, prenom, password, validite, admin) VALUES ('{$_POST['login']}', '{$_POST['nom']}', '{$_POST['prenom']}', '{$_POST['password']}', {$_POST['validite']}, {$_POST['admin']})");
        header('Location: index.php');
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Ecrire un nouveau message</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<div class="wrapper container">
    <div class="row">
        <div class="col">

        </div>
        <div class="col col-md-6">
            <br />
            <h1>Ajouter un nouveau user</h1>
            <hr>
            <form method="POST">
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="validite">Validité (1 = actif)</label>
                    <input type="text" class="form-control" id="validite" name="validite" required>
                </div>
                <div class="form-group">
                    <label for="admin">Rôle (1 = admin)</label>
                    <input type="text" class="form-control" id="admin" name="admin" required>
                </div>
                <br />
                <input type="submit" class="btn btn-lg btn-warning btn-block" value="Ajouter">
            </form>

        </div>
        <div class="col">

        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>

</html>
