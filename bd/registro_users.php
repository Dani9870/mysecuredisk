<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    include 'conexionbd.php';

    $name = $_POST['name'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    function sendMail($name,$email, $v_code){
        require ("../PHPMailer-master/src/PHPMailer.php");
        require ("../PHPMailer-master/src/SMTP.php");
        require ("../PHPMailer-master/src/Exception.php");

        $mail = new PHPMailer(true);
        $conf = getConfiguration();

        try {
            //Server settings
            //$mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $conf["EMAIL_SERVER"];                     //Set the SMTP server to send through
            $mail->SMTPAuth   = TRUE;                                   //Enable SMTP authentication
            $mail->Username   = $conf["EMAIL_ADDR"];                     //SMTP username
            $mail->Password   = $conf["EMAIL_PASSWORD"];                               //SMTP password
            if ($conf["EMAIL_TLS"]=="yes")
                $mail->SMTPSecure = "tls";            //Enable implicit TLS encryption
            $mail->Port       = $conf["EMAIL_PORT"];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            $mail->SetFrom($conf["EMAIL_ADDR"], "MySecureDisk");
            $mail->AddAddress($email);
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Verificacion de correo electronico en MySecureDisk';
            $mail->Body    = "¡Gracias por registrarse!</br>
                Hola ".$name."<br/>"."
                Le damos la bienvenidad a mySecureDisk.</br>
                Necesita confirmar su dirección de correo electrónico para poder comenzar a utilizar la aplicación.<br/>
                Pulse en el enlace de abajo para verificar su email en MySecureDisk<br/>
                <a href='".$conf["APP_URL"]."/verify.php?email=$email&v_code=$v_code'>Verificar correo electronico</a>";
        
            $mail->send();
            error_log("Email enviado a: " . $email);
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar un correo electronico: " . $e);
            return false;
        }
    }
    
    //Hashear contraseña
    $password = hash('sha512', $password);
    $v_code = bin2hex(random_bytes(16));
    $stmt2 = $conexion->prepare("SELECT * FROM users WHERE email=? or username=?");
    $stmt2->bind_param('ss', $email, $username);
    $stmt2->execute();
    $result = $stmt2->get_result();
    //Verificar q no se repita el email en la bd

    if($result->num_rows > 0){
        echo '
            <script>
                alert("Este correo electrónico o nombre de usuario ya se encuentra registrado");
                window.location = "../index.php";
            </script>
        ';
        exit();
    }
    $stmt2->close();
    $query = "INSERT INTO users(name, apellidos, email, username, password, verification_code, is_verified) 
                VALUES(?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($query);
    $verified = 0;
    $stmt->bind_param('ssssssi', $name, $apellidos, $email, $username, $password, $v_code, $verified);
    $stmt->execute();
    sendMail($name,$email, $v_code);

    mysqli_close($conexion);
    header("Location:/registrook.php");

?>