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
    <script>
        function sendmail()
        {
            var value = $("#email").val();
            if (email != "")
            {
                var toSend = {
                    email: value
                };
                $.ajax({
                    url: '/controllers/genpasswordrecovery.php',
                    type: 'post',
                    data: JSON.stringify(toSend),
                    contentType: "application/json",
                    success: (data)=>{
                        location.href="/passwordrecovery2.php";
                    }
                });

            }
        }
    </script>
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

    <div class="container">
    <div class="row" style="margin-top:24px">
        <div class="col-sm-12" style="text-align:center">
            <h1>Solicitud de cambio de contraseña</h1>
        </div>
    </div>
        <div class="row" style="margin-top:24px">
            <div class="col-sm-3"></div>
            <div class="col-sm-3" style="text-align:right">
            Correo electrónico:
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="email"/>
            </div>
            <div class="col-sm-3"></div>
    </div>
    <div class="row" style="margin-top:16px">
        <div class="col-sm-12" style="text-align:center">
            <button type="button" class="btn btn-primary" onclick="sendmail();">Aceptar</button>
        </div>
    </div>
</body>

</html>