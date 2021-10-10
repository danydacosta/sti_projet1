<?php
    session_start();
    $error = false;
    $success = false;

    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    } if(isset($_POST['submit'])) {
        include('dbConnect.php');
        $destinataire = $file_db->query('SELECT * FROM user WHERE login = "'.$_POST['destinataire'].'"')->fetch();
        // vérifier que le destinataire est un utilisateur valide et que l'utilisateur ne s'envoie pas un message
        if(empty($destinataire) || $destinataire['login'] == $_SESSION['userLogin']) {
            $error = true;
        }

        $file_db->exec("INSERT INTO message (expediteur, destinataire, date, sujet, corps) 
                VALUES ('".$_SESSION['userLogin']."', '".$_POST['destinataire']."', '".date_timestamp_get(date_create())."', '".$_POST['sujet']."', '".$_POST['corps']."')");
        
        $success = true;
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
                <?php
                    if($error) {
                       echo '<div class="alert alert-danger" role="alert">
                                Erreur ! Ce destinataire n\'existe pas
                            </div>';
                    } else if ($success) {
                        echo '<div class="alert alert-success" role="alert">
                                Message envoyé !
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
                        <input required class="form-control" name="sujet" placeholder="Mon message" value="<?php echo $_POST['sujet']; ?>">
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