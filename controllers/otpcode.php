<?php
include_once '../otp/FixedBitNotation.php';
include_once '../otp/GoogleAuthenticator.php';
include_once '../otp/GoogleQrUrl.php';
include "../bd/conexionbd.php";

session_start();

class inputVales implements JsonSerializable
{
    public $code='';
    public function jsonSerialize() {
        return $this;
    }
}
if (!isset($_SESSION['user'])) {
    echo "-2";
    session_destroy();
}
$inputObject= json_decode(trim(file_get_contents("php://input")));

$code=$inputObject->code;
$otp_secret = $_SESSION['otp_secret'];
$username=$_SESSION["user"];

error_log("otp_secret: " . $otp_secret);
error_log("username: " . $username);
error_log("code: " . $code);


$query = "select * from users where email=?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ( $result->num_rows > 0){
    error_log("encontrado el registro");
    $row = $result->fetch_assoc();
    $table_otp_secret= $row['otp_secret'];
    $stmt->close();
    $secret = $otp_secret; // desde la sesión por si es la primera vez que se accede
    $save_secret = true;
    if (!($table_otp_secret == null || $table_otp_secret == ""))
    {
        error_log("secret: " . $table_otp_secret);
        $save_secret = false;
        $secret = $table_otp_secret;
    }
    $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
    if ($g->checkCode($secret, $code)) {
        if ($save_secret == true){
            error_log("salvando secreto");
            $stmt2 = $conexion->prepare("update users set otp_secret=? where email=?");
            $stmt2->bind_param("ss", $otp_secret, $username);
            $stmt2->execute();
            $stmt2->close();
        }
        $_SESSION["authenticated"] = "yes";
        return "1";
    } else
        echo "-1";


    
} else {
    $stmt->close();
    echo "-2";
}

?>
