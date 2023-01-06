<?php
include "../bd/conexionbd.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class inputVales implements JsonSerializable
{
    public $email='';
    public function jsonSerialize() {
        return $this;
    }
}


function sendMail($email, $v_code){
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
        $mail->AddAddress($email);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Modificacion de clave de acceso para mySecureDisk!';
        $mail->Body    = "Modificacion de clave de acceso para mySecureDisk!<br/>
            Se ha solicitado un cambio de clave de acceso.<br/>
            Si no ha sido usted no haga caso a este correo electrónico. En caso contrario pulse en el siguiente enlace para
            modificar su clave de acceso: <br/>
            <a href='".$conf["APP_URL"]."/changepassword.php?email=$email&vcode=$v_code'>Modificar clave de acceso</a>";
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$inputObject=json_decode(trim(file_get_contents("php://input")));
if ($inputObject->email != "")
{
    $email = $inputObject->email;
    error_log("Solicitud de cambio de password para: " . $email);
    $stmt = $conexion->prepare("select * from users where email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows>0)
    {
        $stmt->close();

        // cambiar el código de verificación
        $v_code = bin2hex(random_bytes(16));
        $stmt2 = $conexion->prepare("update users set verification_code = ? where email=?");
        $stmt2->bind_param("ss", $v_code,$email);
        $stmt2->execute();
        $stmt2->close();
        sendMail($email, $v_code);
        error_log("Correo de cambio de password enviado a: ".$email);
    } else
        $stmt->close();
}

?>