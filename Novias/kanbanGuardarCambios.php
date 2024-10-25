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
// Actualizar progreso
$sqlTarea = "UPDATE tareas SET aprobado = ?, completado = ?, porcentaje = ?, estatus = CASE 
    WHEN ? = 0 THEN 'pendiente'
    WHEN ? < 100 THEN 'en-curso'
    WHEN ? = 100 AND aprobado = '0' AND completado = '0' THEN 'verificacion'
    WHEN ? = 100 AND aprobado = '1' AND completado = '0' THEN 'contacto-proveedores'
    WHEN ? = 100 AND aprobado = '1' AND completado = '1' THEN 'completado'
    END 
WHERE id = ?";
$stmt = $Conexion->prepare($sqlTarea);
$stmt->bind_param('iiiiiiiii', $validar, $completado, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $nuevoProgreso, $idTarea);
$stmt->execute();

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
