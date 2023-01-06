<?php
include "../bd/conexionbd.php";
session_start();

class inputVales implements JsonSerializable
{
    public $parentId='';
    public $fileName='';
    public function jsonSerialize() {
        return $this;
    }
}

isSessionActive("1");

$inputObject= json_decode(trim(file_get_contents("php://input")));

$filename=$inputObject->fileName;
$parentId = $inputObject->parentId;
if ($parentId == null || $parentId == "")
    $parentId = "-1";
$username=$_SESSION["user"];

$query2 = "delete from files where idParent =? and name=?";
$stmt2 = $conexion->prepare($query2);
$stmt2->bind_param("ss", $parentId,$filename);
$stmt2->execute();
$stmt2->close();
?>
