<?php
include_once('../Conexion/conexion.php');

// Recibir los datos JSON
$data = file_get_contents('php://input');
$decodedData = json_decode($data, true);

$idUsuario = $decodedData['idUsuario'];
$idBoda = $decodedData['idBoda'];
$elementos = $decodedData['elementos'];

if ($elementos && $idUsuario && $idBoda) {
    $detalles = "Los elementos han sido guardados";
    
    // Iterar sobre los elementos recibidos
    foreach ($elementos as $elemento) {
        $nombre = $elemento['nombre'];
        $presupuesto = $elemento['presupuesto'];
        $prioridad = $elemento['prioridad'];
        
        // guardar los datos en la base de datos
        $sqlGuardarElemento = "INSERT INTO elementosboda(evento, usuario, elemento, presupuesto, prioridad) VALUE ('$idBoda', '$idUsuario', '$nombre', '$presupuesto', '$prioridad')";
        $queryGuardarElemento = $Conexion->query($sqlGuardarElemento);
        if($queryGuardarElemento)
        {
            $detalles = "Los elementos han sido guardados";
            
        }
    }

    // Devolver respuesta al cliente (JavaScript)
    echo $detalles;
} else {
    echo "Faltan datos del usuario o de la boda.";
}
?>

