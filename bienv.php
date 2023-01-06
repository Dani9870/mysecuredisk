<?php
include 'bd/conexionbd.php';

isSessionActive("0");

$currentFolder = '-1';
if (isset($_GET['id']) == true) {
    $currentFolder = $_GET['id'];
}
$consulta = "SELECT id , name , date FROM folders WHERE idParentFolders=? order by name";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param('s', $currentFolder);
$stmt->execute();
$guardar = $stmt->get_result();

$consulta2 = "SELECT id , name , date , '0' as isfolder FROM files WHERE idParent=? order by name";
$stmt2 = $conexion->prepare($consulta2);
$stmt2->bind_param('s', $currentFolder);
$stmt2->execute();
$guardar2 = $stmt2->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/aes-pure.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/busqueda.js"></script>
    <style>
        body {
            color: "#696969";
            font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif
        }
        .vcenter {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }
        .li:hover {
            cursor: pointer
        }
    </style>
</head>
<script>
    $("document").ready(() => {
        getKey($("#email").text());
    });
</script>

<body>
<div class="container">
    <div class="row" style="background-color:#425f7e;color:white;border-radius:0 0 10px 10px">
        <div class="col-md-10" style="color:white">
            <h3>MySecureDisk</h3>
        </div>
        <div style="color:blue;text-align:right;margin-bottom:5px;margin-top:5px" class="col-md-2">
            <img src="/images/logo.PNG"/>
        </div>
    </div>
</div>
<div class="modal" id="recoverBox" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Cambio de clave de cifrado</h4>
                </div>
                <div class="modal-body">
                    <p>Edite el archivo de clave de cifrado previamente descargado y pegue el contenido en el siguiente cuadro de texto.</p>
                    <p>Este mecanismo está diseñado para configurar claves de cifrado en navegadores diferentes.</p>
                    <p style="color:red">Esta acción mal ejecutada puede hacer que sus archivos no sean descifrados adecuadamente.</p>
                    <div style="text-align:center"><textarea rows="5" cols="40" id="newkey"></textarea></div>
                </div>
                <div class="modal-footer">
                <button type="button" id="actionbutton" class="btn btn-success" data-dismiss="modal"
                    onclick="loadKeyFromFileContent($('#newkey').val());$('#recoverBox').modal('hide');">Aceptar</button>
            </div>
        </div>

        </div>
    </div>


    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div style="margin-top:24px">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownuser"
                        data-toggle="dropdown">
                        <span id="email" style="margin-right:16px"><?php echo htmlspecialchars($_SESSION['user']); ?></span><span class="caret"></span></button></button>
                    <ul class="dropdown-menu" >
                        <li ><a class="dropdown-item" href="/changepasswordinside.php">Cambiar contraseña</a></li>
                        <li ><a class="dropdown-item" onclick="saveKey();" href="#">Descargar clave de cifrado</a></li>
                        <li ><a class="dropdown-item" onclick="$('#recoverBox').modal('show')" href="#">Recargar clave de cifrado</a></li>
                        <div style=""><hr/></div>
                        <li ><a class="dropdown-item" href="bd/cerrar_sesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <h3 class="text-center">Tu almacenamiento</h3>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <label for="caja_busqueda" >Buscar: </label>
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="caja_busqueda" id="caja_busqueda" />
                </div>
                <div class="col-sm-8" style="text-align:right">
                    <button id="newfolder" class="btn btn-primary" type="button" data-toggle="modal"
                        data-target="#newfoldermodal">
                        <span class="glyphicon glyphicon-plus" style="margin-right:24px"></span>Nueva carpeta
                    </button>
                </div>
            </div>
            <div class="row">
                <div id="draganddroparea" class="drop-area" draggable="true"
                    style="border-style:dashed;margin-top:24px;text-align:center" ondrop="dropFile(event);"
                    ondragover="dragoverFile(event);">
                    <h2 draggable="true">Arrastra tus archivos aquí</h2>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive table-hover" id="tablaConsulta">
                    <div id="datos">

                    </div>
                </div>
            </div>



        </div>

        <!-- Modal Create -->
        <div class="modal fade" id="newfoldermodal" role="dialog">
            <div class="modal-dialog">
                <form id="nuevaCarpeta" method="post" action="controllers/newfolder.php">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Crear nueva carpeta</h4>
                        </div>
                        <div class="modal-body">
                            <p>Nombre de la carpeta</p>
                            <input type="text" name="newFolderName" id="newFolderName" />
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" data-dismiss="modal" onclick="newFolder();">Aceptar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="modal" id="sendingFiles" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Enviando archivos</h4>
                    </div>
                    <div class="modal-body">
                        <p id="fileNameToSend">...</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Modify -->
        <div class="modal fade" id="modifymodal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modificar el nombre de una carpeta</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <span>Nombre actual:</span>&nbsp;<span id="oldnamefolder"></span>
                        </div>
                        <div>
                            <span>Nombre nuevo:</span>&nbsp;<input type="text" id="modifyFolderName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                            onclick="onModifyNameFolder();">Modificar</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Remove -->
        <div class="modal fade" id="removemodal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="messagetitle" class="modal-title">Eliminar registro</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message">¿Está seguro de que quiere eliminar este registro?</p>
                        <input type="hidden" id="messageid" />
                        <form method="POST" action="eliminar.php" id="form-delete-register">
                            <input type="text" id="nameRemove" placeholder="Escriba el nombre">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="deletebutton" form="form-delete-register" class="btn btn-danger"
                            data-dismiss="modal" onclick="doDeleteFolder();">Eliminar</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!--<script src="js/bootstrap.min.js"></script>-->
</body>

</html>