<?php
session_start();


    if(!isset($_SESSION['attempt'])){
        $_SESSION['attempt']=0;
    }
    if(isset($_SESSION['attempt_again'])) {
        $now = time();
        if ($now >= $_SESSION['attempt_again']) {
            unset($_SESSION['attempt']);
            unset($_SESSION['attempt_again']);
        }
    }

        if (isset($_POST['sbmt-login'])) {
            if (isset($_POST['inputLogin']) && isset($_POST['inputPassword'])) {
                try {
                    include 'dbConnect.php';

                    $login = $_POST['inputLogin'];
                    $password = $_POST['inputPassword'];

                    $sth = $file_db->prepare('SELECT * FROM user WHERE login = ?');
                    $sth->execute(array($login));
                    $data = $sth->fetch();

                    if (!empty($data)) { // Si vide, le user n'existe pas dans la base
                        if ($data['validite'] == 0) {
                            echo "<script type='text/javascript'>alert('Login failed');</script>";
                        } else if (hash('sha256', $password) == $data["password"]) {    // Credentials justes, login accepté
                            $_SESSION["userLogin"] = $login;
                            header('Location: ' . "index.php");
                            exit;
                        } else {
                            $_SESSION['attempt'] += 1;
                          if($_SESSION['attempt']>=3)
                            {
                                echo "<script type='text/javascript'>alert('Too many failed attempt, please wait');</script>";
                                if(!isset($_SESSION['attempt_again'])) { //On évite d'augmenter le temps à chaque essai
                                    $_SESSION['attempt_again'] = time() + 15; //15s
                                }
                            } else
                            {
                                echo "<script type='text/javascript'>alert('Login failed');</script>";
                            }
                        }
                    } else {
                        echo "<script type='text/javascript'>alert('User does not exist');</script>";
                    }
                } catch (PDOException $e) {
                    // Print PDOException message
                    echo $e->getMessage();
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

    <title>Login page</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
  <form class="form-signin" method="POST">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputLogin" class="sr-only">Login</label>
      <input type="text" id="inputLogin" name="inputLogin" class="form-control" placeholder="Login" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
      <input type="submit" name="sbmt-login" class="btn btn-lg btn-primary btn-block" value="Submit">
  </form>


  </body>
</html>
