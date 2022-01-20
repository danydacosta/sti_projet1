<?php
    session_start();
    $error = false;
    $success = false;

    if(isset($_GET['disconnect'])) {
        unset($_SESSION['userLogin']);
        header('Location: login.php');
    }

    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } 
    
    if(isset($_POST['submit'])) {
        if(isset($_POST['destinataire']) && isset($_POST['sujet']) && isset($_POST['corps'])) {
            include('dbConnect.php');
            $sth = $file_db->prepare('SELECT * FROM user WHERE login = ?');
            $sth->execute(array($_POST['destinataire']));
            $destinataire = $sth->fetch();
    
            // vérifier que le destinataire est un utilisateur valide et que l'utilisateur ne s'envoie pas un message
            if(empty($destinataire) || $destinataire['login'] == $_SESSION['userLogin']) {
                $error = true;
            } else {
                $sth = $file_db->prepare('INSERT INTO message (expediteur, destinataire, date, sujet, corps) VALUES (?, ?, ?, ?, ?)');
                $sth->execute(array($_SESSION['userLogin'], $_POST['destinataire'], date_timestamp_get(date_create()), $_POST['sujet'], $_POST['corps']));
        
                $success = true;
                header("Refresh:3; url=index.php");
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
                    if($error) {
                       echo '<div class="alert alert-danger" role="alert">
                                Erreur ! Ce destinataire n\'existe pas
                            </div>';
                    } else if ($success) {
                        echo '<div class="alert alert-success" role="alert">
                                Message envoyé ! Redirection dans 3 secondes...
                            </div>';
                    }
                ?>
                <br />
                <h1>Ecrire un nouveau message</h1>
                <hr>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Destinataire</label>
                        <input required class="form-control" name="destinataire" placeholder="doej" value="<?php echo $_GET['dest']; ?>">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Sujet</label>
                        <input required class="form-control" name="sujet" placeholder="Mon message" value="<?php if(isset($_POST['sujet'])) { echo $_POST['sujet']; } else { echo $_GET['sujet']; } ?>">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Message</label>
                        <textarea required class="form-control" name="corps" rows="3"><?php echo $_POST['corps']; ?></textarea>
                    </div>
                    <br />
                    <button type="submit" class="btn btn-success" name="submit">
                        Envoyer
                    </button>
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