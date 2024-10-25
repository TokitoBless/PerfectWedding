<?php

include_once('../Conexion/conexion.php');

function noviaOnovio()
{
    global $Conexion;
    $idUsuario = $_GET['idUsuario'];
    $sqlTipoUsuarios = "SELECT * FROM usuarios WHERE id = '$idUsuario' AND (tipoUsuario = 'Novia' OR tipoUsuario = 'Novio')";
    $queryTipoUsuarios = $Conexion->query($sqlTipoUsuarios);

    if (mysqli_num_rows($queryTipoUsuarios) > 0) {
        return true;
    }else{
        return false;
    }
  
}

if (isset($_GET['idUsuario'])) {
    $mostrarDiv = noviaOnovio();
}
else {
    header('Location: validaciones.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

?>
