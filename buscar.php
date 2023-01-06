<?php

require 'bd/conexionbd.php';

isSessionActive("1");

$carpeta = -1;
if (isset($_POST['carpeta']))
    if ($_POST['carpeta'] != "")
        $carpeta = $_POST['carpeta'];
$salida = "";
$query = "SELECT * FROM folders where ownername=?";
$query2 = "SELECT * FROM files where ownername=?";
$textoConsulta = "";
if (isset($_POST['consulta'])) {
    $textoConsulta = $conexion->real_escape_string($_POST['consulta']);
    $query = $query .
        " AND name LIKE ?";

    $query2 = $query2 .
        " AND name LIKE ?";
}
else
{
    $query = $query . " AND idParentFolders=?";
    $query2 = $query2 . " AND idParent=?";

}

$query = $query . " order by name";
$query2 = $query2 . " order by name";

$stmt = $conexion->prepare($query);
$stmt2 = $conexion->prepare($query2);


if ($textoConsulta == "") {
    $stmt->bind_param('ss', $username, $folder);
    $username = $_SESSION['user'];
    $folder = $carpeta;
    $stmt2->bind_param('ss', $username, $folder);
    $username = $_SESSION['user'];
    $folder = $carpeta;
} else {
    $stmt->bind_param('ss', $username, $toSearch);
    $username = $_SESSION['user'];
    $toSearch = '%'.$textoConsulta.'%';
    $stmt2->bind_param('ss', $username, $toSearch);
    $username = $_SESSION['user'];
    $toSearch = '%'.$textoConsulta.'%';
}


if ($carpeta != -1) {
    $query3 = "select idParentFolders,name from folders where id=? and ownername=?";
    $stmt3 = $conexion->prepare($query3);
    $stmt3->bind_param('ss', $carpeta,$username);
    $stmt3->execute();
    $resultado3 = $stmt3->get_result();
    $fila = $resultado3->fetch_assoc();
    $stmt3->close();
    $salida .= "<div style='margin-top:10px'>Carpeta actual: <strong>" . htmlspecialchars($fila['name']) . "</strong><br/><input type=hidden id='currentFolder' value='".$carpeta."'></div>";
}

$salida .= "<table class='table table-responsive table-hover'>
<thead class='thead-dark'>
    <tr>
        <td width='55%'><b>Archivos</b></td>
        <td width='25%' ><b>Fecha de creación</b></td>
        <td width='15%' style='text-align:right'><b>Acciones</b></td>
    </tr>
</thead>
<tbody>";
if ($carpeta != -1) {
    $salida .= "<tr>
    <td><span class='glyphicon glyphicon-arrow-left' style='color:black'></span><a style='padding-left:10px' href='bienv.php?carpeta=" . $fila['idParentFolders'] . "'><b>Atrás</b></a></td>
    <td>" . $fila['date'] . "</td>
    <td style='text-align:right'></td>
    </tr>";

}
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows > 0) {


    while ($fila = $resultado->fetch_assoc()) {
        $salida .= "<tr>
                    <td><span class='glyphicon glyphicon-folder-open' style='color:blue;font-size:20px;'></span><a style='padding-left:10px' href='bienv.php?carpeta=" . $fila['id'] . "'><b>" . htmlspecialchars($fila['name']) . "</b></a></td>
                    <td>" . $fila['date'] . "</td>
                    <td style='text-align:right'><i id='modifyy'style='cursor: pointer' ><span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Modificar nombre de la carpeta' ><span class='glyphicon glyphicon-edit' style='color: bluegreen;margin-right:10px;font-size:20px;' onclick='modifyNameFolder(\"".htmlspecialchars($fila['name'])."\")'></span></span></i><i id='remove' style='cursor: pointer'><span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar carpeta' ><span class='glyphicon glyphicon-remove-sign' style='color: red;font-size:20px;' onclick='deleteFolder(\"".htmlspecialchars($fila["name"])."\")'></span></span></i></td>
        </tr>";
    }
}
$stmt->close();
$stmt2->execute();
$resultado2 = $stmt2->get_result();
if ($resultado2->num_rows > 0) {
    while ($fila2 = $resultado2->fetch_assoc()) {
        $salida .= "<tr>
                    <td><span class='glyphicon glyphicon-file' style=';color:rgb(254,80,0);font-size:20px;'></span><span style='padding-left:10px'>" . htmlspecialchars($fila2['name']) . "</span></td>
                    <td>" . $fila2['date'] . "</td>
                    <td style='text-align:right'><i id='download' style='cursor: pointer'><span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Descargar archivo' ><span class='glyphicon glyphicon-download' style='color: green;margin-right:10px;font-size:20px;' onclick='getFileStream(\"".htmlspecialchars($fila2["name"])."\",".$carpeta.");'></span></span><i id='removee' style='cursor: pointer'><span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar archivo' ><span class='glyphicon glyphicon-remove-sign' style='color: red;font-size:20px;' onclick='deleteFile(\"".htmlspecialchars($fila2["name"])."\")'></span></span></i></td>
        </tr>";
    }
}
$stmt2->close();
$salida .= "</tbody></table>";

echo $salida;

$conexion->close();
?>