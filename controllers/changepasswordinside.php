<?php
include "../bd/conexionbd.php";

isSessionActive("0");

error_log("cambiando password....");
$inputObject = json_decode(trim(file_get_contents("php://input")));
$username = $_SESSION['user'];
$oldpassword = $inputObject->oldpassword;
error_log("Usuario: ".$username);
if ($inputObject->password == null || $inputObject->password == "" || $username == null || $username == "" || $oldpassword == null || $oldpassword == "" ) {
    echo "-3";
} else {
    $hashedpassword = hash('sha512', $inputObject->password);
    $hashedoldpassword = hash('sha512', $oldpassword);

    $stmt = $conexion->prepare("select id from users where email=? and password=?");
    $stmt->bind_param("ss", $username, $hashedoldpassword);
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
        $stmt2 = $conexion->prepare("update users set password=? where id=?");
        $stmt2->bind_param("si", $hashedpassword, $id);
        $stmt2->execute();
        echo "1";
    }
}

?>