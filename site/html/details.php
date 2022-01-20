<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } else if(!isset($_GET['id'])) {
        header('Location: index.php');
    }

    if(isset($_GET['disconnect'])) {
        unset($_SESSION['userLogin']);
        header('Location: login.php');
    }

    include('dbConnect.php');
    $sth = $file_db->prepare('SELECT * FROM message WHERE id = ?');
    $sth->execute(array($_GET['id']));
    $message = $sth->fetch();

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
    <title>Détails de <?php echo htmlspecialchars($message['sujet']); ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="wrapper container">
        <div class="row">
            <div class="col">

            </div>
            <div class="col col-md-6">
                <br>
                <div class="float-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    <b>
                        <?php echo $_SESSION['userLogin']; ?>
                    </b>

                    <button type="button" class="btn btn-danger btn-sm" style="margin-left: 10px;" onclick="window.location.href = '/index.php?disconnect=true'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                            <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                            <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z"/>
                        </svg>
                    </button>
                </div>
                <br />
                <?php
                echo '<h1>'.htmlspecialchars($message['sujet']).'</h1>
                <br />
                <h5><b>Date de réception : </b> '.date('d.m.Y', htmlspecialchars($message['date'])).'</h5>
                <h5><b>Expéditeur : </b> '.$message['expediteur'].'</h5>
                <hr>
                <h5><b>Corps : </b></h5>
                <p>
                    '.htmlspecialchars($message['corps']).'
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