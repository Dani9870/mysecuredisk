<?php
require "fileController.php";
session_start();
isSessionActive("1");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller= new fileController($requestMethod,$conexion);
exit ($controller->processRequest($_GET,trim(file_get_contents("php://input"))));
?>
