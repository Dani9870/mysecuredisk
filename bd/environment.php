<?php
include("configuration.php");
function getConfiguration ()
{
    $array = array();
    if (!isset($_ENV["DB_DATABASE"]))
    {
        error_log("Leyendo configuracion de configuration.php ...");
        $array["DB_DATABASE"]=DB_DATABASE;
        $array["DB_HOST"]=DB_HOST;
        $array["DB_PASSWORD"]=DB_PASSWORD;
        $array["DB_USERNAME"]=DB_USERNAME;
        $array["DB_ISSSL"]=DB_ISSSL;
        $array["DB_CACERT"]=DB_CACERT;
        $array["APP_URL"]=APP_URL;
        $array["EMAIL_ADDR"]=EMAIL_ADDR;
        $array["EMAIL_PASSWORD"]= EMAIL_PASSWORD;
        $array["EMAIL_SERVER"]= EMAIL_SERVER;
        $array["EMAIL_PORT"] = EMAIL_PORT;
        $array["EMAIL_TLS"] = EMAIL_TLS;

    }
    else
    {
        error_log("Leyendo configuracion de variables de entorno ...");
        $array["DB_DATABASE"]=$_ENV["DB_DATABASE"];
        $array["DB_HOST"]=$_ENV["DB_HOST"];
        $array["DB_PASSWORD"]=$_ENV["DB_PASSWORD"];
        $array["DB_USERNAME"]=$_ENV["DB_USERNAME"];
        $array["DB_ISSSL"]=$_ENV["DB_ISSSL"];
        $array["DB_CACERT"]=$_ENV["DB_CACERT"];
        $array["APP_URL"]=$_ENV["APP_URL"];
        $array["EMAIL_ADDR"]=$_ENV["EMAIL_ADDR"];
        $array["EMAIL_PASSWORD"]= $_ENV["EMAIL_PASSWORD"];
        $array["EMAIL_SERVER"]= $_ENV["EMAIL_SERVER"];
        $array["EMAIL_PORT"] = $_ENV["EMAIL_PORT"];
        $array["EMAIL_TLS"] = $_ENV["EMAIL_TLS"];
    }
    return $array;
}
?>