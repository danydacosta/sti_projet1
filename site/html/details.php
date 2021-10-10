<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } else if(!isset($_GET['id'])) {
        header('Location: index.php');
    }

    include('dbConnect.php');

    $message =  $file_db->query('SELECT * FROM message WHERE id = "'.$_GET['id'].'"')->fetch();

    $destinataire = $message['destinataire'];
    $userLogin = $_SESSION['userLogin'];

    // le message existe
    if(empty($message)) {
        header('Location: index.php');
    }
    // Vérifier que l'id du message concerne bien la personne connectée
    else if (strcmp($destinataire, $userLogin) !== 0) {
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
    <title>Détails</title>

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
                <?php
                echo '<h1>'.$message['sujet'].'</h1>
                <br />
                <h5><b>Date de réception : </b> '.date('d.m.Y', $message['date']).'</h5>
                <h5><b>Expéditeur : </b> '.$message['expediteur'].'</h5>
                <hr>
                <h5><b>Détails : </b></h5>
                <p>
                    '.$message['corps'].'
                </p>';
                ?>
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