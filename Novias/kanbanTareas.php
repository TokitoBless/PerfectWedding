<?php
include_once('./validacionesUsuarios.php');
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: panelGeneral.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

// Consulta para obtener las tareas
$sqlTareas = "
    SELECT * FROM tareas 
    WHERE idUsuario = ? 
    AND idBoda = ? 
    ORDER BY prioridad DESC, estatus ASC
";
$stmt = $Conexion->prepare($sqlTareas);
$stmt->bind_param('ii', $idUsuario, $idBoda);
$stmt->execute();
$result = $stmt->get_result();

$tareas = [];

while ($row = $result->fetch_assoc()) {
    $tareas[] = $row;
}

$stmt->close();

header('Content-Type: application/json');
// Enviar las tareas al frontend como JSON puro
echo json_encode($tareas);
