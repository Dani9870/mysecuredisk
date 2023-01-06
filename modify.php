<?php
session_start();
include 'bd/conexionbd.php';
isSessionActive("0");

$id = $_GET['id'];
$m = "SELECT * from folders WHERE id = '$id'";
$modificar = $conexion->query($m);
$dato = $modificar->fetch_array();

if (isset($_POST['modificar'])) {
    $id = $_POST['id'];
    $nombre = $conexion->real_escape_string($_POST['mNombre']);

    $actualiza = "UPDATE folders SET name = '$nombre' WHERE id = '$id'";
    $actualizar = $conexion->query($actualiza);
    header("location:bienv.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    
    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <a href="bd/cerrar_sesion.php">Cerrar sesión</a>
            <h3 class="text-center">Modificar Registros</h3>
            <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="row">
                    <input type="hidden" name="id" value="<?php echo $dato['id']; ?>">
                    <input type="text" name="mNombre" class="form-control" value="<?php echo $dato['name']?>" placeholder="Nombre" required>
                </div>
                <div class="row">
                    <input type="submit" name="modificar" class="btn btn-success btn-sm btn-block" value="Modificar">
                </div>
        
            </form>
            
        </div>
    </div>


    <script src="js/bootstrap.min.js"></script>
</body>
</html>