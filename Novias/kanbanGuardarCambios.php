<?php
include_once('../Conexion/conexion.php');

$data = json_decode(file_get_contents('php://input'), true);
$idTarea = $data['idTarea'];
$nuevoProgreso = $data['porcentaje'];
$nuevoComentario = $data['comentario'];
$idUsuario = $data['idUsuario'];
$idBoda = $data['idBoda'];
$validar = $data['validar'] ? '1' : '0';
$completado = $data['completado'] ? '1' : '0';
$idTareaPadre = $data['idTareaPadre'];
if($idTareaPadre != '0'){ //Es subtarea?
    // Actualizar progreso de la subtarea
    $sqlSubTarea = "UPDATE tareas SET porcentaje = ?, estatus = CASE 
    WHEN ? = 0 THEN 'pendiente'
    WHEN ? < 100 THEN 'en-curso'
    WHEN ? = 100 THEN 'completado'
    END 
    WHERE id = ?";
    $stmt = $Conexion->prepare($sqlSubTarea);
    $stmt->bind_param('iiiii', $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $idTarea);
    $stmt->execute();
    //Revisar promedio y subtareas
    $sqlcuantasSubtareas = "SELECT count(*) as numeroSubtareas, AVG(porcentaje) as promedio from tareas where idTarea = ?";
    $stmt = $Conexion->prepare($sqlcuantasSubtareas);
    $stmt->bind_param('i', $idTareaPadre);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $promedioSubtareas = intval((float)$row['promedio']);
    switch (true) {
        case ($promedioSubtareas < 25):
            $promedioTarea = 0;
            break;
        case ($promedioSubtareas >= 25 && $promedioSubtareas < 50):
            $promedioTarea = 25;
            break;
        case ($promedioSubtareas >= 50 && $promedioSubtareas < 75):
            $promedioTarea = 50;
            break;
        case ($promedioSubtareas >= 75 && $promedioSubtareas < 100):
            $promedioTarea = 75;
            break;
        case ($promedioSubtareas == 100):
            $promedioTarea = 100;
            break;
        default:
            $promedioTarea = 0; 
    }
    // Actualizar progreso de la tarea padre
    $sqlTarea = "UPDATE tareas SET porcentaje = ?, estatus = CASE 
    WHEN ? = 0 THEN 'pendiente'
    WHEN ? < 100 THEN 'en-curso'
    WHEN ? = 100 AND aprobado = '0' THEN 'verificacion'
    WHEN ? = 100 AND aprobado = '1' AND completado = '0' THEN 'contacto-proveedores'
    WHEN ? = 100 AND aprobado = '1' AND completado = '1' THEN 'completado'
    END 
    WHERE id = ?";
    $stmt = $Conexion->prepare($sqlTarea);
    $stmt->bind_param('iiiiiii', $promedioTarea, $promedioTarea, $promedioTarea, $promedioTarea, $promedioTarea, $promedioTarea, $idTareaPadre);
    $stmt->execute();
}
else{
    // Actualizar progreso de Tarea padre sin subtareas
    $sqlTarea = "UPDATE tareas SET aprobado = ?, completado = ?, porcentaje = ?, estatus = CASE 
    WHEN ? = 0 THEN 'pendiente'
    WHEN ? < 100 THEN 'en-curso'
    WHEN ? = 100 AND aprobado = '0' THEN 'verificacion'
    WHEN ? = 100 AND aprobado = '1' AND completado = '0' THEN 'contacto-proveedores'
    WHEN ? = 100 AND aprobado = '1' AND completado = '1' THEN 'completado'
    END 
    WHERE id = ?";
    $stmt = $Conexion->prepare($sqlTarea);
    $stmt->bind_param('iiiiiiiii', $validar, $completado, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $idTarea);
    $stmt->execute();
}

// Insertar nuevo comentario
if (!empty($nuevoComentario)) {
    $sqlComentario = "INSERT INTO comentariostareas (idTarea, idBoda, idUsuario, comentario, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmtComentario = $Conexion->prepare($sqlComentario);
    $stmtComentario->bind_param('iiis', $idTarea, $idBoda, $idUsuario, $nuevoComentario);
    $stmtComentario->execute();
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
