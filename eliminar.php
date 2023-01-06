<?php
include 'bd/conexionbd.php';
isSessionActive();

$id = $_GET['id'];
$eliminar = "DELETE FROM folders WHERE id = '$id'";
$eliminar = $conexion->query($eliminar);
header("location:bienv.php");
$conexion->close();

?>