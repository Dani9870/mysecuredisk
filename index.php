<?php
    session_start();

    if(isset($_SESSION['user'])){
        header("location: bienv.php");
    }
    require 'bd/conexionbd.php';

    /*$query = "update users set password = '$password' ";
    $result = mysqli_query($conexion, $query);
    */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/functions.js"></script>

    <link rel="icon" type="image/png" href="images/logo.PNG" >
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script>
        $("document").ready(()=>{
            $(function(){
             $('#registro').on('submit', function(event){
                const _password = $("#password").val();
            if ( strongRegex.test(_password))
                return true;
            else
            {
                
                $("#notmatch").html("La contraseña debe tener una minúscula, una mayúscula, un número, un caracter especial y ser de longitud mayor a 8");
                $("#notmatch").removeClass("hidden");
                event.preventDefault();
                return false;
            }

        });
});
        });
    </script>
</head>
<body>
    <!--<img class="logo" src="images/logo.PNG" >-->
    <main>
        <div class="contenedor_main">
            <div class="caja_back">
                <div class="caja_back_login">
                    <h3>¿Ya tiene una cuenta?</h3>
                    <p>Inicia sesión para poder acceder a la aplicación</p>
                    <button id="btn_login">Iniciar sesión</button>
                </div>
                <div class="caja_back_registro">
                    <h3>¿Ya tiene una cuenta?</h3>
                    <p>Regístrate para poder acceder a la aplicación</p>
                    <button id="btn_registro">Registrarse</button>
                </div>
            </div>
            <!--FORMULARIO LOGIN-REGISTRO-->
            <div class="contenedor_login-registro">
                <form action="bd/login_users.php" method="POST" class="form_login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" name="email" placeholder="Ingrese su correo electrónico">
                    <input type="password" name="password" placeholder="Ingrese su contraseña">
                    <button>Iniciar sesión</button><br><br>
                    <a href="/passwordrecovery.php">¿Olvidó su contraseña?</a>
                </form>
                
                <form action="bd/registro_users.php" method="POST" class="form_registro" id="registro">
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Ingrese su nombre" name="name">
                    <input type="text" placeholder="Ingrese sus apellidos" name="apellidos">
                    <input type="text" placeholder="Ingrese su usuario" name="username">
                    <input type="text" placeholder="Ingrese su correo electrónico" name="email">
                    <input type="password" placeholder="Ingrese su contraseña" name="password" id="password">
                    <p id="notmatch" class="hidden" style="color:red">La contraseña es demasiado débil</p>
                    <input type="submit" value="Registrarse" id="registerbutton"/>
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>