<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
    $idBoda = $_GET['idBoda'];
    $idTablero = isset($_GET['idTablero']) ? $_GET['idTablero'] : '';
    
    switch ($tipo) {
        case 'tablerosGuardados':
            $consulta = "SELECT * from servicios s where s.id in (
            select sg.idServicio from serviciosguardados sg where sg.idTablero = '$idTablero')";
            break;
        case 'tablerosCompartidos':
            $consulta = "SELECT * from servicios s where s.id in (
            select sg.idServicio from serviciosguardados sg where sg.idTablero = '$idTablero')";
            break;
        case 'serviciosCompartidos':
            $consulta = "SELECT * from servicios s where s.id in (
            select idServicio from servicioscompartidos
                where idUsuario = '$idUsuario')";
            break;
        default:
            $consulta = "";
    }

    $resultado = $Conexion->query($consulta);
    $datos = [];
    $html = "";
    
    /*if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }
    }*/

    if ($resultado) {
        $html = "<div class='card-container'>";
        while ($row = $resultado->fetch_assoc()) {
            $idServicio = $row['id'];
            $idProveedor = $row['proveedor'];
            $nombreServicio = $row['nombreServicio'];
            $descripcionServicio = $row['descripcion'];
            $precio = $row['precio'];
            $calificacion = $row['calificacion'];
            $categoria = $row['categoria'];
            $palabraClave = $row['palabraClave'];
    
            $sqlInformacionProveedor = "SELECT * FROM proveedores WHERE id = '$idProveedor'";
            $queryInformacionProveedor= $Conexion->query($sqlInformacionProveedor);
            $rowProveedor = $queryInformacionProveedor->fetch_assoc();
    
            $nombreProveedor = $rowProveedor['nombre'] . " " . $rowProveedor['apellidoPaterno'] . " " . $rowProveedor['apellidoMaterno'];
            $sitioWeb = $rowProveedor['sitioWeb'];
    
            // Preparar imágenes
            $imagenes = [];
            for ($i = 1; $i <= 5; $i++) {
                $campoImagen = 'imagen' . $i;
                if (!empty($row[$campoImagen])) {
                    $imagenes[] = 'data:image/jpeg;base64,' . base64_encode($row[$campoImagen]);
                }
            }
            $html =  $html . "
            <div class='card' style='width: 12rem;' data-bs-toggle='modal' data-bs-target='#serviceModal' 
                data-id='$idServicio' data-idUsuario='$idUsuario' data-idBoda='$idBoda' data-categoria='$categoria' data-palabraClave='$palabraClave' data-cali='$calificacion' data-images='" . json_encode($imagenes) . "' data-name='$nombreServicio' data-descrip='$descripcionServicio' data-precio='$precio' data-nombreProveedor='$nombreProveedor' data-sitioWeb='$sitioWeb'>
                <div class='card-img-container'>
                    <img src='{$imagenes[0]}' class='card-img-top' alt='$nombreServicio'>
                </div>
                <div class='card-body'>
                    <h5 class='card-title'>$nombreServicio</h5>
                </div>
            </div>";
        $html =  $html . "</div>";
        }
    }

    
    echo $html;

   // echo json_encode($html);
}
?>

