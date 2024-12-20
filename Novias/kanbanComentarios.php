<?php
include_once('../Conexion/conexion.php');
header('Content-Type: application/json');

$idTarea = $_GET['idTarea'];

$sql = "SELECT * FROM comentariostareas WHERE idTarea = ?";
$sql = "SELECT c.*, u.usuario FROM comentariostareas c JOIN usuarios u on c.idUsuario = u.id WHERE idTarea = ?";
$stmt = $Conexion->prepare($sql);
$stmt->bind_param('i', $idTarea);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

$stmt->close();

echo json_encode($comentarios);
?>
