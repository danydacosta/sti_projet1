<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    include_once 'utils.php';

    if (!checkAdmin($_SESSION['userLogin'])) {
        header('Location: index.php');
    }

    if(isset($_POST['sbm-user'])) {
        $csrf_token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);
        if(!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
            header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
            exit;
        }

        if (isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['password']) && isset($_POST['validite']) && isset($_POST['admin'])) {
            $file_db = dbConnection();
    
            // Vérifier que l'utilisateur n'existe pas déjà
            $sth = $file_db->prepare('SELECT * FROM user WHERE login = ?');
            $sth->execute(array($_POST['login']));
            $data = $sth->fetch();
    
            if (empty($data)) {
                // Vérifier que le mot de passe contient au moins 8 char et un chiffre
                if(passwordPolicy($_POST['password'])) {
                    // Les valeurs pour validite et admin doivent être 1 ou 0
                    if(($_POST['validite'] == '0' || $_POST['validite'] == '1') && ($_POST['admin'] == '0' || $_POST['admin'] == '1')) {
                        $sth = $file_db->prepare('INSERT INTO user (login, nom, prenom, password, validite, admin) VALUES (?, ?, ?, ?, ?, ?)');
                        $sth->execute(array($_POST['login'], $_POST['nom'], $_POST['prenom'], hash('sha256', $_POST['password']), $_POST['validite'], $_POST['admin']));
            
                        header('Location: index.php');
                    } else {
                        echo "<script type='text/javascript'>alert('Validite and admin value must be 0 or 1');</script>";
                    }
                } else {
                    echo "<script type='text/javascript'>alert('Password must be min 8 caracters length, contain a number and an upper case');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('User already exists');</script>";
            }   
        } else {
            echo "<script type='text/javascript'>alert('All parameters must be filled!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Ajouter un nouveau user</title>

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
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?: '' ?>" />
                <input type="submit" name="sbm-user" class="btn btn-lg btn-warning btn-block" value="Ajouter">
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
