<?php
    session_start();
    $error = array();

    require 'mail.php';

    
    if(!$conexion = mysqli_connect("localhost", "root", "", "aplicaciontfg")){
        die("No se ha podido conectar");
    }

    $mode = "enter_email";
    if(isset($_GET['mode'])){
        $mode = $_GET['mode'];
    }

    if(count($_POST) > 0){
        switch($mode){
            case 'enter_email' :

                $email = $_POST['email'];
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $error[] = "Por favor, ingrese un correo valido";
                }elseif(!valid_email($email)){
                    $error[] = "El email no se ha encontrado";
                }else{

                    $_SESSION['forgot']['email'] = $email;
                    send_email($email);
                    header("Location: forgot.php?mode=enter_code");
                    die;
                }
                break;
            case 'enter_code' :

                $code = $_POST['code'];
                $result = is_code_correct($code);

                if($result == "El código es correcto"){

                    $_SESSION['forgot']['code'] = $code;
                    header("Location: forgot.php?mode=enter_password");
                    die;
                }else{
                    $error[] = $result;
                }
                break;
            case 'enter_password' :

                $password = $_POST['password'];
                $password2 = $_POST['password2'];

                if($password !== $password2){
                    $error[] = "Las contraseñas no coinciden";
                }elseif(!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])){
                    header("Location: forgot.php");
                    die;
                }else{
                    save_password($password);
                    if(isset($_SESSION['forgot'])){
                        unset($_SESSION['forgot']);
                    }

                    header("Location: index.php");
                    die;
                }
                break;

            default :

                break;
        }
    }

    function valid_email($email){

        global $conexion;
        $email = addslashes($email);

        $query = "select * from users where email = '$email' limit 1";
        $result = mysqli_query($conexion,$query);
        
        if($result){
            if(mysqli_num_rows($result) > 0){
                    return true;
            }
        }

        return false;

    }


    function save_password($password){
        
        global $conexion;
        //$password = password_hash($password, PASSWORD_DEFAULT);
        $email = addslashes($_SESSION['forgot']['email']);

        $query = "update users set password = '$password' where email = '$email' limit 1";
        mysqli_query($conexion,$query);

    }

    function send_email($email){
        
        global $conexion;
        $expire = time() + (60 * 1);
        $code = rand(10000, 99999);
        $email = addslashes($email);

        $query = "insert into codes (email, code, expire) value ('$email', '$code', '$expire')";
        mysqli_query($conexion,$query);

        send_mail($email, 'Recuperar contraseña', "Tu código es " . $code);
    }

    function is_code_correct($code){

        global $conexion;
        $code = addslashes($code);
        $expire = time();
        $email = addslashes($_SESSION['forgot']['email']);

        $query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
        $result = mysqli_query($conexion,$query);

        if($result){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                if($row['expire'] > $expire){
                    return "El código es correcto";
                }else{
                    return "El código se ha expirado";
                }
            }else{
                return "El código es incorrecto";
            }
        }
        
        return false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <main>
        <div class="contenedor_main">
            <div class="contenedor_login-registro">
                <?php

                    switch($mode){
                        case 'enter_email' :
                        ?>
                        
                                <form action="forgot.php?mode=enter_email" method="POST" class="form_login">
                                    <h2>Recuperar contraseña</h2>
                                    <h3>Ingrese su correo aquí</h3>
                                    <span style="font-size: 12px;color:red">
                                    <?php
                                        foreach ($error as $err) {
                                            # code...
                                            echo $err. "<br>";
                                        }
                                    ?>
                                    </span>
                                    <input type="text" name="email" placeholder="Ingrese su correo electrónico">
                                    <input type="submit" value="Siguiente">
                                    <a href="index.php">Inicia sesión</a>
                                </form>
                    <?php
                    
                        break;
                    case 'enter_code' :

                    ?>
                                <form action="forgot.php?mode=enter_code" method="POST" class="form_login">
                                    <h2>Recuperar contraseña</h2>
                                    <h3>Ingrese el código que se ha mandado a su email</h3>
                                    <span style="font-size: 12px;color:red">
                                    <?php
                                        foreach ($error as $err) {
                                            # code...
                                            echo $err. "<br>";
                                        }
                                    ?>
                                    </span>
                                    <input type="text" name="code" placeholder="Ingrese el código">
                                    <input type="submit" value="Siguiente" style="float: right;">
                                    <a href="forgot.php">
                                        <input type="button" value="Volver">
                                    </a>
                                    <a href="index.php">Inicia sesión</a>
                                </form>
                    <?php

                        break;
                    case 'enter_password' :

                    ?>
                                <form action="forgot.php?mode=enter_password" method="POST" class="form_login">
                                    <h2>Recuperar contraseña</h2>
                                    <h3>Ingrese su nueva contraseña</h3>
                                    <span style="font-size: 12px;color:red">
                                    <?php
                                        foreach ($error as $err) {
                                            # code...
                                            echo $err. "<br>";
                                        }
                                    ?>
                                    </span>
                                    <input type="text" name="password" placeholder="Ingrese la contraseña">
                                    <input type="text" name="password2" placeholder="Repita la contraseña">
                                    <input type="submit" value="Siguiente" style="float: right;">
                                    <a href="forgot.php">
                                        <input type="button" value="Volver">
                                    </a>
                                    <a href="index.php">Inicia sesión</a>
                                </form>
                    <?php

                        break;
                    default :

                        break;
                }
            
            ?>
            </div>
        </div>
    </main>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>