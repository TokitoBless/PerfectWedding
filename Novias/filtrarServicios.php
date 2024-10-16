<?php
include_once('../Conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $precioMax = isset($_POST['precioMax']) ? (int)$_POST['precioMax'] : 0;
    $calificacion = isset($_POST['calificacion']) ? (int)$_POST['calificacion'] : 0;
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $palabraClave = isset($_POST['palabraClave']) ? $_POST['palabraClave'] : '';
    $listaCategorias = isset($_POST['listaCategorias']) ? $_POST['listaCategorias'] : '';

    // Armar consulta SQL con los filtros
    $sqlServicios = "SELECT * FROM servicios WHERE categoria IN ($listaCategorias)";

    if ($categoria != '') {
        $sqlServicios .= " AND categoria = '$categoria'";
    }

    if ($palabraClave != '') {
        $sqlServicios .= " AND palabraClave LIKE '%$palabraClave%'";
    }

    if ($precioMax > 0) {
        $sqlServicios .= " AND precio <= $precioMax";
    }

    if ($calificacion > 0) {
        $sqlServicios .= " AND calificacion >= $calificacion";
    }
    $queryServicios = $Conexion->query($sqlServicios);

    /*
    if ($queryServicios->num_rows > 0) {
        while ($row = $queryServicios->fetch_assoc()) {
            $idServicio = $row['id'];
            $nombreServicio = $row['nombreServicio'];
            $descripcionServicio = $row['descripcion'];
            $precio = "$" . $row['precio'];
            $calificacion = $row['calificacion'];

            // Preparar imágenes
            $imagenes = [];
            for ($i = 1; $i <= 5; $i++) {
                $campoImagen = 'imagen' . $i;
                if (!empty($row[$campoImagen])) {
                    $imagenes[] = 'data:image/jpeg;base64,' . base64_encode($row[$campoImagen]);
                }
            }

            echo "
            <div class='card' style='width: 12rem;' data-bs-toggle='modal' data-bs-target='#serviceModal' 
                data-id='$idServicio' data-cali='$calificacion' data-images='" . json_encode($imagenes) . "' data-name='$nombreServicio' data-descrip='$descripcionServicio' data-precio='$precio'>
                <div class='card-img-container'>
                    <img src='{$imagenes[0]}' class='card-img-top' alt='$nombreServicio'>
                </div>
                <div class='card-body'>
                    <h5 class='card-title'>$nombreServicio</h5>
                </div>
            </div>";
        }
    } else {
        echo "No se encontraron servicios que coincidan con los filtros.";
    }
        */
}
?>
