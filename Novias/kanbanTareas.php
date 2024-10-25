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
    SELECT tareas.*, CONCAT(u.nombre, ' ', u.apellidoPaterno, ' ', u.apellidoMaterno) AS nombreEncargado,
    (SELECT COUNT(*) FROM tareas t WHERE t.idTarea = tareas.id) AS numeroSubtareas 
    FROM tareas 
    JOIN usuarios u on u.id = tareas.idEncargado
        WHERE (tareas.idUsuario = ? OR tareas.idEncargado = ?)
        AND tareas.idBoda = ? 
        ORDER BY tareas.prioridad DESC, tareas.estatus ASC
";
$stmt = $Conexion->prepare($sqlTareas);
$stmt->bind_param('iii', $idUsuario, $idUsuario, $idBoda);
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
