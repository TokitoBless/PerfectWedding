<?php
include_once('../Conexion/conexion.php');
header('Content-Type: application/json');

$idServicio = $_GET['idServicio'];
$sql = "SELECT r.*, u.usuario, (SELECT COUNT(*) FROM resenas WHERE idServicio = ?) AS totalResenas FROM resenas r JOIN usuarios u on r.idUsuario = u.id WHERE r.idServicio = ? ORDER BY r.fecha DESC";
$stmt = $Conexion->prepare($sql);
$stmt->bind_param('ii',$idServicio, $idServicio);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}
$sqlCaliificacion = "SELECT ROUND(AVG(calificacion)) AS calificacionPromedio FROM resenas WHERE idServicio = ?";
$stmt = $Conexion->prepare($sqlCaliificacion);
$stmt->bind_param('i', $idServicio);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $calificacionPromedio = $row['calificacionPromedio'];

    $sqlUpdate = "UPDATE servicios SET calificacion = ? WHERE id = ?";
    $stmt = $Conexion->prepare($sqlUpdate);
    $stmt->bind_param('ii',$calificacionPromedio, $idServicio);
    $stmt->execute();
}





echo json_encode($comentarios);
?>
