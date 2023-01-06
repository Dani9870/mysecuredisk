<?php
include ("environment.php");

$conf = getConfiguration();

    if(!$conexion = getDBConnection($conf["DB_DATABASE"],$conf["DB_HOST"],$conf["DB_PASSWORD"],$conf["DB_USERNAME"],$conf["DB_ISSSL"],$conf["DB_CACERT"])){
        die("No se ha podido conectar a la base de datos");
    }
    function getDBConnection ($db_database,$db_host,$db_password,$db_username,$db_isssl,$db_cacert)
    {
        //modifique el archivo .env para modificar los datos de conexión
        $con = mysqli_init();
        if ( $db_isssl =="yes" )
        {
            error_log ("La conexion a bbdd es SSL...");
            mysqli_options($con,MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
            $con->ssl_set(null,null,$db_cacert,null,null);
            $con->real_connect($db_host, $db_username, $db_password, $db_database,'3306',null,MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
        }
        else
            $con->real_connect($db_host, $db_username, $db_password,$db_database);
        return $con;
    }
    function isSessionActive ($isajax)
    {
        session_start();
        if (!isset($_SESSION['user']))
        {
            if ($isajax == "1")
                echo "";
            else
                header("Location:/index.php");
            session_destroy();
            exit();
        }
        if (!isset($_SESSION['authenticated']))
        {
            if ($isajax == "1")
                echo "";
            else
                header("Location:/index.php");
            session_destroy();
            exit();
        }
        if ($_SESSION['authenticated']!="yes")
        {
            if ($isajax == "1")
                echo "";
            else
                header("Location:/index.php");
            session_destroy();
            exit();
        }
    }
?>
