<?php
include "../bd/conexionbd.php";
session_start();

class inputVales implements JsonSerializable
{
    public $parentId='';
    public $folderName='';
    public function jsonSerialize() {
        return $this;
    }
}

isSessionActive("1");


$inputObject= json_decode(trim(file_get_contents("php://input")));

$foldername=$inputObject->folderName;
$parentId = $inputObject->parentId;
if ($parentId == null || $parentId == "")
    $parentId = "-1";
$username=$_SESSION["user"];

$query="select * from folders where idParentFolders=? and ownername=? and name=?";

$stmt= $conexion->prepare($query);
$stmt->bind_param("sss",$parentId,$username,$foldername);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows>0)
{
    $fila = $result->fetch_assoc();
    $parentId = $fila["id"];
    $stmt->close();
    $query2 = "delete from files where idParent =?";
    $stmt2 = $conexion->prepare($query2);
    $stmt2->bind_param("s", $parentId);
    $stmt2->execute();
    $stmt2->close();
    $query4 = "delete from folders where idParentFolders =?";
    $stmt4 = $conexion->prepare($query4);
    $stmt4->bind_param("s", $parentId);
    $stmt4->execute();
    $stmt4->close();
    $query3 = "delete from folders where id =?";
    $stmt3 = $conexion->prepare($query3);
    $stmt3->bind_param("s", $parentId);
    $stmt3->execute();
    $stmt3->close();

}

?>
