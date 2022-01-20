<?php

    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    include_once 'utils.php';
    $file_db = dbConnection();

    $userToEdit = "";
    if(!isset($_GET['login'])) {        // Il est obligatoire d'avoir un id user en paramètre
        header('Location: login.php');
    } else {
        $userToEdit = $_GET['login'];
    }

    $user = $_SESSION['userLogin'];


    if (!checkAdmin($user)) {           // Il faut être admin pour accéder cette page
        header('Location: index.php');
    }

    $sth = $file_db->prepare('SELECT login, validite, admin FROM user WHERE login = ?');
    $sth->execute(array($userToEdit));
    $userData = $sth->fetch();

    if (isset($_POST['sbmt-edit'])) {
        if (isset($_POST['inputLogin']) && isset($_POST['validite']) && isset($_POST['role']) && isset($_POST['password'])) {
            $file_db = dbConnection();
    
            // Vérifier que l'utilisateur existe
            $sth = $file_db->prepare('SELECT * FROM user WHERE login = ?');
            $sth->execute(array($_POST['inputLogin']));
            $data = $sth->fetch();
    
            if (!empty($data)) {
                // Vérifier que le mot de passe contient au moins 8 char et un chiffre
                if(strlen($_POST['password']) >= 8 && preg_match('~[0-9]+~', $_POST['password'])) {
                    // Les valeurs pour validite et role doivent être 1 ou 0
                    if(($_POST['validite'] == '0' || $_POST['validite'] == '1') && ($_POST['role'] == '0' || $_POST['role'] == '1')) {
                        $sth = $file_db->prepare('UPDATE user SET validite = ?, admin = ?, password = ? WHERE login = ?');
                        $sth->execute(array($_POST['validite'], $_POST['role'], hash('sha256', $_POST['password']), $_POST['inputLogin']));

                        header('Location: index.php');
                    } else {
                        echo "<script type='text/javascript'>alert('Validite and role value must be 0 or 1');</script>";
                    }
                } else {
                    echo "<script type='text/javascript'>alert('Password must be min 8 caracters length and contain a number');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('User does not exists');</script>";
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

    <title>Modifier utilisateur</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" method="POST">
    <h1 class="h3 mb-3 font-weight-normal">Modifier utilisateur</h1>
    <label for="inputLogin" class="sr-only">Login</label>
    <input type="text" id="inputLogin" name="inputLogin" class="form-control" placeholder="Login" value="<?php echo $userData['login'] ?>" readonly>
    <label for="validite" class="sr-only">Validite (1 = actif)</label>
    <input type="text" id="validite" name="validite" class="form-control" value="<?php echo $userData['validite'] ?>" required>
    <label for="role" class="sr-only">Rôle (admin = 1)</label>
    <input type="text" id="role" name="role" class="form-control" value="<?php echo $userData['admin'] ?>" required>
    <label for="password" class="sr-only">Mot de passe</label>
    <input type="password" id="password" name="password" class="form-control" required>
    <br>
    <input type="submit" name="sbmt-edit" class="btn btn-lg btn-warning btn-block" value="Appliquer les modifs">
</form>


</body>
</html>
