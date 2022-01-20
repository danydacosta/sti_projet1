<?php
    session_start();
    if(!isset($_SESSION['userLogin'])) {
        header('Location: login.php');
    }

    $user = $_SESSION['userLogin'];
    include_once 'utils.php';

    if(isset($_GET['disconnect']) || !checkValid($user)) {
        unset($_SESSION['userLogin']);
        header('Location: login.php');
    }

    include('dbConnect.php');

    $userAdmin = checkAdmin($user);  // Boolean pour dire si admin ou pas. true = admin, false pas admin

    $sth = $file_db->prepare('SELECT * FROM message WHERE destinataire = ?');
    $sth->execute(array($_SESSION['userLogin']));
    $messages = $sth->fetchAll();

    if(isset($_GET['del'])) {
        $csrf_token = filter_input(INPUT_GET, 'csrf_token', FILTER_SANITIZE_STRING);
        if(!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
            header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
            exit;
        }
        
        // le message à supprimer doit être un message de l'utilisateur
        $found = false;
        foreach($messages as $key => $value) {
            if(array_search($_GET['del'], $value) !== false) {
                $found = true;
                unset($messages[$key]);
            }
        } 

        if($found) {
            $sth = $file_db->prepare('DELETE FROM message WHERE id = ?');
            $sth->execute(array($_GET['del']));
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
    <title>Messages</title>

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
                <button type="button" class="btn btn-warning" onclick='window.location.href = "changeMdp.php"'>Changer le MDP</button>
                <?php
                    if ($userAdmin) {
                        echo '<button type="button" class="btn btn-warning" onclick=\'window.location.href = "gestUsers.php"\'>Gestion users</button>';
                    }
                ?>

                <br />
                <h1>Messages</h1>
                <br />

                <button type="button" class="btn btn-success" onclick='window.location.href = "new_msg.php"'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg>
                </button>
                <br />
                <br />
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date réception</th>
                            <th scope="col">Expéditeur</th>
                            <th scope="col">Sujet</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($messages as $row) {
                                $sth = $file_db->prepare('SELECT prenom, nom FROM user WHERE login =  ?');
                                $sth->execute(array($row['expediteur']));
                                $expeditor = $sth->fetch();

                                echo '<tr>
                                        <td>'.date('d.m.Y', $row['date']).'</td>
                                        <td>'.$expeditor['prenom'].' '.$expeditor['nom'].'</td>
                                        <td>'.$row['sujet'].'</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" onclick=\'window.location.href = "/new_msg.php?dest='.$row['expediteur'].'&sujet=RE: '.$row['sujet'].'"\'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
                                                    <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z" />
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm(\'Supprimer le message ?\')){ window.location.href = \'/index.php?del='.$row['id'].'&csrf_token='.$_SESSION['csrf_token'].'\'; }else{event.stopPropagation(); event.preventDefault();};">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" onclick=\'window.location.href = "/details.php?id='.$row['id'].'"\'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-open" viewBox="0 0 16 16">
                                                    <path d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.818l5.724 3.465L8 8.917l1.276.766L15 6.218V5.4a1 1 0 0 0-.53-.882l-6-3.2zM15 7.388l-4.754 2.877L15 13.117v-5.73zm-.035 6.874L8 10.083l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738zM1 13.117l4.754-2.852L1 7.387v5.73zM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765l6-3.2z" />
                                                </svg>
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