<?php
$username = $_GET['email'];
$vcode = $_GET['vcode'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/aes-pure.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/busqueda.js"></script>
    <style>
        body {
            color: "#696969";
            font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif
        }

        .vcenter {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row" style="background-color:#425f7e;color:white;border-radius:0 0 10px 10px">
        <div class="col-md-10" style="color:white">
            <h3>MySecureDisk</h3>
        </div>
        <div style="color:blue;text-align:right;margin-bottom:5px;margin-top:5px" class="col-md-2">
            <img src="/images/logo.PNG"/>
        </div>
    </div>
</div>

    <div class="modal" id="messageBox" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Enviando archivos</h4>
                </div>
                <div class="modal-body">
                    <p id="message">...</p>
                </div>
                <div class="modal-footer">
                <button type="button" id="actionbutton" class="btn btn-success" data-dismiss="modal"
                    onclick="location.href='/bienv.php';">Aceptar</button>
            </div>
        </div>

        </div>
    </div>
    <div class="container">
    <div class="row" style="margin-top:24px">
            <div class="col-sm-12" style="text-align:center">
                <h1>Contrase??a actual</h1>
            </div>
        </div>
        <div class="row" style="margin-top:24px">
            <div class="col-sm-2"></div>
            <div class="col-sm-4" style="text-align:right">
                Contrase??a:
            </div>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="oldpassword" />
            </div>
            <div class="col-sm-2"></div>
        </div>
        <div class="row" style="margin-top:24px">
            <div class="col-sm-12" style="text-align:center">
                <h1>Introduzca su nueva contrase??a</h1>
            </div>
        </div>
        <div class="row" style="margin-top:24px">
            <div class="col-sm-2"></div>
            <div class="col-sm-4" style="text-align:right">
                Contrase??a:
            </div>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="password" />
            </div>
            <div class="col-sm-2"></div>
        </div>
    <div class="row" style="margin-top:24px">
        <div class="col-sm-2"></div>
        <div class="col-sm-4" style="text-align:right">
            Repetir contrase??a:
        </div>
        <div class="col-sm-4">
            <input type="password" class="form-control" id="password2" />
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div class="row" style="margin-top:16px">
        <div class="col-sm-2"></div>
        <div class="col-sm-8" style="text-align:right">
            <p id="notmatch" class="hidden" style="color:red">Las contrase??as no coinciden o la contrase??a es demasiado
                d??bil</p>
            <button type="button" class="btn btn-primary" onclick="savePasswordInside();">Aceptar</button>
        </div>
        <div class="col-sm-2"></div>
    </div>
  </div>

</body>

</html>