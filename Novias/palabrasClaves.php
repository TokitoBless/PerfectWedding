<?php
include_once('../Conexion/conexion.php');

// Asegúrate de que la variable POST 'categoria' está definida
if (isset($_POST['categoria']) && !empty($_POST['categoria'])) {
    $categoria = $_POST['categoria'];

    // Intenta ejecutar la consulta
    $sql = "SELECT palabra FROM palabrasClaves WHERE categoria = '$categoria'";
    $result = $Conexion->query($sql);

    // Verifica si hay errores en la consulta
    if ($Conexion->error) {
        echo "Error en la consulta SQL: " . $Conexion->error;
        exit();
    }

    $palabras = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $palabras[] = $row['palabra'];
        }
    }

    // Configura el encabezado para JSON
    header('Content-Type: application/json');
    echo json_encode($palabras); // Devuelve las palabras clave en formato JSON
} else {
    echo json_encode(["error" => "Categoría no definida o vacía."]);
}
?>
