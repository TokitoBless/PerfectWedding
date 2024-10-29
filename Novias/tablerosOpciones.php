<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
    
    switch ($tipo) {
        case 'tablerosGuardados':
            $consulta = "SELECT * FROM tablerospersonalizados where idUsuario = '$idUsuario'";
            break;
        case 'tablerosCompartidos':
            $consulta = "SELECT tp.* FROM tablerospersonalizados tp where tp.id = (
            select tc.idTablero from tableroscompartidos tc where tc.idUsuario = '$idUsuario')";
            break;
        default:
            $consulta = "";
    }
    $resultado = $Conexion->query($consulta);
    $datos = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $datos[] = $row; // Esto crea un array de objetos
    }

    echo json_encode($datos);
}
?>
