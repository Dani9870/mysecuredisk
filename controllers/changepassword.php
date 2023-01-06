<?php
include "../bd/conexionbd.php";

class inputValues implements JsonSerializable
{
    public $password = '';
    public function jsonSerialize()
    {
        return $this;
    }
}
error_log("cambiando password....");
$inputObject = json_decode(trim(file_get_contents("php://input")));
$username = $inputObject->email;
$v_code = $inputObject->vcode;
error_log("Usuario: ".$username);
error_log("V code: ".$v_code);
session_destroy();
if ($inputObject->password == null || $inputObject->password == "" || $username == null || $username == "" || $v_code == null || $v_code == "" ) {
    echo "-3";
} else {
    $hashedpassword = hash('sha512', $inputObject->password);

    $stmt = $conexion->prepare("select id from users where email=? and verification_code=?");
    $stmt->bind_param("ss", $username, $v_code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt->close();
        echo "-1";
        error_log("No coincide el codigo de verificacion para el usuario: " . $username);
    } else {
        $row = $result->fetch_assoc();
        $id = $row["id"];
        $stmt->close();
        $verificationcode = bin2hex(random_bytes(16)); // para no volver a utlizar un email antiguo
        $stmt2 = $conexion->prepare("update users set password=?,verification_code=? where id=?");
        $stmt2->bind_param("ssi", $hashedpassword,$verificationcode, $id);
        $stmt2->execute();
        echo "1";
    }
}

?>