<?php

include_once('../Conexion/conexion.php');
// Crear un array para almacenar las categorías y palabras
$categorias = array();

// Consulta para obtener todas las palabras clave
$sqlBoda = "SELECT categoria, palabra FROM palabrasclaves";
$queryBoda = $Conexion->query($sqlBoda);


// Recorrer los resultados y agrupar por categoría
while ($row = $queryBoda->fetch_assoc()) {
    // Agregar la palabra a la categoría correspondiente
    $categorias[$row['categoria']][] = $row['palabra'];
}

// Mostrar el resultado
foreach ($categorias as $categoria => $opciones) {
    echo "<br>$categoria:\n";
    foreach ($opciones as $opcion) {
        echo "- $opcion\n";
    }
}


?>