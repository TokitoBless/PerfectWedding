<?php
include_once('../Conexion/conexion.php');

$categoria = $_POST['categoria'];

$sql = "SELECT palabra FROM palabrasClaves WHERE categoria = '$categoria'";
$result = $Conexion->query($sql);

$palabras = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $palabras[] = $row['palabra'];
    }
}

echo json_encode($palabras); // Devuelve las palabras clave en formato JSON

?>