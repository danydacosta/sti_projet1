<?php

    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    $userToEdit = "";
    if(!isset($_GET['login'])) {    // Il est obligatoire d'avoir un id user en paramètre
        header('Location: login.php');
    } else {
        $userToEdit = $_GET['login'];
    }

    include_once 'utils.php';
    $user = $_SESSION['userLogin'];
    $file_db = dbConnection();

    if (!checkAdmin($user)) {   // Il faut être admin pour accéder cette page
        header('Location: index.php');
    }

    $userData = $file_db->query("SELECT login, validite, admin FROM user WHERE login = '{$userToEdit}'")->fetch();

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit user</title>

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
    <input type="text" id="inputLogin" class="form-control" placeholder="Login" value="<?php echo $userData['login'] ?>" readonly>
    <label for="validite" class="sr-only">Validite (1 = actif)</label>
    <input type="text" id="validite" name="validite" class="form-control" value="<?php echo $userData['validite'] ?>" required>
    <label for="role" class="sr-only">Rôle (admin = 1)</label>
    <input type="text" id="role" name="role" class="form-control" value="<?php echo $userData['admin'] ?>" required>
    <br>
    <input type="submit" class="btn btn-lg btn-warning btn-block" value="Appliquer les modifs">
</form>


</body>
</html>
