<?php
    include_once '../otp/FixedBitNotation.php';
    include_once '../otp/GoogleAuthenticator.php';
    include_once '../otp/GoogleQrUrl.php';


    include 'conexionbd.php';

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = hash('sha512', $password);

    $stmt = $conexion->prepare("SELECT * FROM users WHERE email=? and password=? and is_verified=1");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_ok=false;
    $register_otp=false;
    $img_url="";
    if ($result->num_rows > 0)
    {
        $user_ok = true;
        session_start();
        $_SESSION['user'] = $email;
        $_SESSION['authenticated'] = "no";
        $_SESSION["valid_otp"] = "no";

        $fila = $result->fetch_assoc();
        $verification_code = $fila["otp_secret"];
        error_log("Verification code: " . $verification_code);
        if ($verification_code == null || $verification_code == "")
        {
            $register_otp =true;
            $stmt->close();
            $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            // generación de la semilla
            $secret = $g->generateSecret();
            $_SESSION["otp_secret"] = $secret;
            // url para generar QR para la aplicación de autenticación
            $img_url = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($email, $secret, 'mySecureDisk');
        }
        else
            $stmt->close();

    }
    else
    {
        $stmt->close();
        header("location: ../index.php");
    }
if ($user_ok) {
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
        function callAuthenticator()
        {
            var value = $("#inputcode").val();
            if (value != "")
            {
                var toSend = {
                    code: value
                };
                $.ajax({
                    url: '/controllers/otpcode.php',
                    type: 'post',
                    data: JSON.stringify(toSend),
                    contentType: "application/json",
                    success: (data)=>{
                        //a ver que pasa
                        if (data == "1")
                        {
                            location.href="/bienv.php";
                        }
                        else
                        if (data == "-1")
                        {
                            $("#messageerror").removeClass("hidden");
                        }
                        else
                        {
                            location.href="/index.php";
                        }
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
<?php
  if ($register_otp == true){
?>
  <div class="row">
    <div class="col-sm-12">
        A continuación, debe configurar el autenticador de google o de microsoft de su teléfono movil.<br/>Para ello
        añada una nueva cuenta en el autenticador y escanee el código de barras mostrado.</br>
        Una vez escaneado introduzca el código que le muestra en pantalla.
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12" style="text-align:center;margin-top:32px">
      <img src="<?php echo $img_url; ?>"/>
    </div>
  </div>
<?php
  }
?>
  <div class="row">
  <div class="col-sm-2"></div>
    <div class="col-sm-8" style="text-align:center;margin-top:32px">
        Introduzca el código proporcionado por el autenticador:
    </div>
    <div class="col-sm-2"></div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4" style="text-align:center;margin-top:16px">
        <input type="text" class="form-control" id="inputcode"/>
    </div>
    <div class="col-sm-4"></div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4" style="text-align:center;margin-top:16px;color:red">
        <span id="messageerror" class="hidden">Código erróneo.</span>
    </div>
    <div class="col-sm-4"></div>
  </div>
  <div class="row">
  <div class="col-sm-5"></div>
    <div class="col-sm-2" style="text-align:center;margin-top:16px">
        <button type="button" class="btn btn-primary" onclick="callAuthenticator();">Aceptar</button>
    </div>
    <div class="col-sm-5"></div>
  </div>

</div>
</body>
<?php
}
?>