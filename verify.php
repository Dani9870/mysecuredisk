<?php
    require("bd/conexionbd.php");

    if(isset($_GET['email']) && isset($_GET['v_code'])){

        $query = "SELECT * FROM users WHERE email = ? AND verification_code = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $_GET['email'], $_GET["v_code"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
                    $stmt->close();
                    $update = "UPDATE users SET is_verified = 1,verification_code='' WHERE email = ?";
                    $stmt2 = $conexion->prepare($update);
                    $stmt2->bind_param("s",$_GET["email"]);
                    $stmt2->execute();
                echo"
                <script>
                    location.href='index.php';
                </script>
                ";
            
        }
        else
        {
            header("Location:/registroerror.php");
        }
    }
?>