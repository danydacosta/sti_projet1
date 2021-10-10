<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    include_once 'utils.php';

    $file_db = dbConnection();
    $user = $_SESSION['userLogin'];

    if (!checkAdmin($user)) {
        header('Location: index.php');
    }

    $messages =  $file_db->query("SELECT login, validite, admin FROM user")->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Gestion utilisateurs</title>

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
            <h1>Utilisateurs</h1>
            <br />

            <button type="button" class="btn btn-success" onclick='window.location.href = "addUser.php"'>
                Ajouter un utilisateur
            </button>
            <br />
            <br />
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Login</th>
                    <th scope="col">Validité</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($messages as $row) {
                    echo '<tr>
                                        <td>'.$row['login'].'</td>
                                        <td>'.$row['validite'].'</td>
                                        <td>'.$row['admin'].'</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" onclick=\'window.location.href = "editUser.php?login='.$row['login'].'"\'>
                                                Modifier
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick=\'window.location.href = "delUser.php?login='.$row['login'].'"\'>
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>';
                }
                ?>
                </tbody>
            </table>
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
