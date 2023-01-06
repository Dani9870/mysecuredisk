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
$now = new DateTime();
$currentDate = $now->getTimestamp();

$query="select * from folders where idParentFolders=? and ownername=? and name=?";

$stmt= $conexion->prepare($query);
$stmt->bind_param("sss",$parentId,$username,$foldername);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows==0)
{
    $stmt->close();
    $query2 = "INSERT INTO folders (idParentFolders,name,date,ownername) values (?,?,FROM_UNIXTIME(?),?)";
    $stmt2 = $conexion->prepare($query2);
    $stmt2->bind_param("ssds", $parentId, $foldername, $currentDate, $username);
    $stmt2->execute();
}

?>
