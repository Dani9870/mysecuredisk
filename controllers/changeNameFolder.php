<?php
include "../bd/conexionbd.php";
session_start();

class inputVales implements JsonSerializable
{
    public $parentId='';
    public $folderName='';
    public $newFolderName = '';
    public function jsonSerialize() {
        return $this;
    }
}
isSessionActive("1");

$inputObject= json_decode(trim(file_get_contents("php://input")));

$foldername=$inputObject->folderName;
$parentId = $inputObject->parentId;
$newfolderName = $inputObject->newFolderName;
if ($parentId == null || $parentId == "")
    $parentId = "-1";
$username=$_SESSION["user"];

$query="select * from folders where idParentFolders=? and ownername=? and name=?";

$stmt= $conexion->prepare($query);
$stmt->bind_param("sss",$parentId,$username,$newfoldername);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    $stmt->close();
    $query2 = "update folders set name=? where idParentFolders =? and ownername=? and name=?";
    $stmt2 = $conexion->prepare($query2);
    $stmt2->bind_param("ssss", $newfolderName, $parentId, $username, $foldername);
    $stmt2->execute();
    $stmt2->close();
} else
    $stmt->Close();
?>
