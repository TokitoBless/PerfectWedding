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

        // Verificar si el elemento ya existe
        $sqlVerificarElemento = "SELECT * FROM elementosboda WHERE evento = '$idBoda' AND elemento = '$nombre'";
        $queryVerificarElemento = $Conexion->query($sqlVerificarElemento);

        if ($queryVerificarElemento->num_rows > 0) {
            // Si existe, actualizar el registro
            $sqlActualizarElemento = "UPDATE elementosboda SET presupuesto = '$presupuesto' WHERE evento = '$idBoda' AND elemento = '$nombre'";
            $queryActualizarElemento = $Conexion->query($sqlActualizarElemento);
            
            if($queryActualizarElemento) {
                $detalles = "Los elementos han sido actualizados correctamente.";
            } else {
                $detalles = "Error al actualizar el elemento: " . $Conexion->error;
            }
        } else {
            
            $sqlInsertarElemento = "INSERT INTO elementosboda(evento, usuario, elemento, presupuesto, prioridad) VALUE ('$idBoda', '$idUsuario', '$nombre', '$presupuesto', '$prioridad')";
            $queryInsertarElemento = $Conexion->query($sqlInsertarElemento);
            if($queryInsertarElemento) {
                $detalles = "Los elementos han sido guardados";
            } else {
                $detalles = "Error al guardar el elemento: " . $Conexion->error;
            }
        }
    }

    
    echo $detalles;
} else {
    echo "Faltan datos del usuario o de la boda.";
}
?>


