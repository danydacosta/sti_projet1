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
    <div class="row">
        <div class="col">

        </div>
        <div class="col col-md-6">
            <br />
            <h1>Ecrire un nouveau message</h1>
            <hr>
            <form>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Destinataire</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="doej">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Message</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <br />
                <button type="submit" class="btn btn-success">
                    Envoyer
                </button>
            </form>

        </div>
        <div class="col">

        </div>
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>

</html>