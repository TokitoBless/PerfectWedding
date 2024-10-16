<?php
include_once('../Conexion/conexion.php');

// Obtener el contenido JSON
$data = json_decode(file_get_contents('php://input'), true);

$idServicio = $data['idServicio'];
$idBoda = $data['idBoda'];
$idUsuario = $data['idUsuario'];
$categoria = $data['categoria'];
$precio = $data['precio'];
$palabraClave = $data['palabraClave'];
$checkFavorito = $data['checkFavorito'];


$sqlIdCategoria = "SELECT idElemento, presupuesto FROM elementosboda WHERE elemento = '$categoria'";
$queryIdCategoria = $Conexion->query($sqlIdCategoria);
$row = $queryIdCategoria->fetch_assoc();
$idCategoria = $row['idElemento'];
$presupuesto = $row['presupuesto'];

$sqlIdPalabraClave = "SELECT id FROM palabrasclaves WHERE palabra = '$palabraClave'  AND categoria = '$categoria'";
$queryIdPalabraClave = $Conexion->query($sqlIdPalabraClave);
$row = $queryIdPalabraClave->fetch_assoc();
$idPalabraClave = $row['id'];

$sqlExisteRegistro = "SELECT id FROM algoritmos WHERE idEvento = '$idBoda' AND idUsuario = '$idUsuario' AND idServicio = '$idServicio' AND idCategoria = '$idCategoria' AND  idPalabraClave = '$idPalabraClave' ";
$queryExisteRegistro = $Conexion->query($sqlExisteRegistro);
if(mysqli_num_rows($queryExisteRegistro) > 0 && $checkFavorito=='1'){
    $row = $queryExisteRegistro->fetch_assoc();
    $sqlUpdate = "UPDATE algoritmos SET favorito = '2' WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND id = '" . $row['id'] . "'";
    $queryUpdate = $Conexion->query($sqlUpdate);
}

if ($checkFavorito=='0'){
    $sqlRegistro = "SELECT * FROM algoritmos WHERE idEvento = '$idBoda'AND idUsuario = '$idUsuario' AND  idCategoria = '$idCategoria' AND  idPalabraClave = '$idPalabraClave' ";
    $queryRegistro = $Conexion->query($sqlRegistro);
    if(mysqli_num_rows($queryRegistro) > 0){
        $row = $queryRegistro->fetch_assoc();
        $cont = $row['contador'] + 1;
        $sqlUpdate = "UPDATE algoritmos SET contador = '$cont' WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND id = '" . $row['id'] . "'";
        $queryUpdate = $Conexion->query($sqlUpdate);
        if ($precio > $presupuesto) {
            $contPre = $row['contadorPresupuesto'] + 1;
            $costoPromedio = ($row['costoPromedio'] + $precio);
            $sqlUpdate = "UPDATE algoritmos SET contadorPresupuesto = '$contPre', costoPromedio  = '$costoPromedio' WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND id = '" . $row['id'] . "'";
            $queryUpdate = $Conexion->query($sqlUpdate);
        }
    }else {
        if ($precio > $presupuesto) {
            //echo $precio, $presupuesto;
            $sqlGuardarElementoPresupuesto = "INSERT INTO algoritmos(idEvento, idUsuario, idServicio, idCategoria, idPalabraClave, favorito, contador, contadorPresupuesto, costoPromedio) VALUE ('$idBoda', '$idUsuario' , '$idServicio', '$idCategoria', '$idPalabraClave', '0', '1', '1', '$precio')";    
            $queryGuardarElementoPresupuesto = $Conexion->query($sqlGuardarElementoPresupuesto);
        }
        else{
            $sqlGuardarElemento = "INSERT INTO algoritmos(idEvento, idUsuario, idServicio, idCategoria, idPalabraClave, favorito, contador, contadorPresupuesto, costoPromedio) VALUE ('$idBoda', '$idUsuario', '$idServicio', '$idCategoria', '$idPalabraClave', '0', '1', '0', '0')";    
            $queryGuardarElemento = $Conexion->query($sqlGuardarElemento);
        }
    }
}

$sqlchecarPresupuesto = "SELECT idCategoria, SUM(favorito) AS totalFavoritos, SUM(contadorPresupuesto) AS totalContador, SUM(costoPromedio) AS totalCosto FROM algoritmos WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND idCategoria = $idCategoria AND contadorPresupuesto > 0";
$querychecarPresupuesto = $Conexion->query($sqlchecarPresupuesto);
if(mysqli_num_rows($querychecarPresupuesto) > 0){
    $row = $querychecarPresupuesto->fetch_assoc();
    $contadorP = 5;
    if ($row['totalContador']+$row['totalFavoritos']>=$contadorP){
        $costoPromedioTotal = $row['totalCosto']/$row['totalContador'];
        $fecha = new DateTime();
        $fechaCreacion = $fecha->format('Y-m-d H:i:s'); 
        $notificacion = "Ya te pasaste con el presupuesto en la categoria " . $categoria . " viendo servicios con un costo promedio de: $" . $costoPromedioTotal . ", tu presupuesto inicial es de: $" . $presupuesto;
        $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idUsuario', 'Precios mayores al presupuesto', '$fechaCreacion', '$notificacion')";    
        $queryGuardarElementoNotificacion = $Conexion->query($sqlGuardarNotificacion);
        echo $notificacion;
        $sqlUpdate = "UPDATE algoritmos SET contadorPresupuesto = '0', costoPromedio  = '0' WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND idCategoria = '" . $row['idCategoria'] . "'";
        $queryUpdate = $Conexion->query($sqlUpdate);
    }
}

$contadorS = 5;
$sqlchecarSugeridos = "SELECT idPalabraClave FROM algoritmos WHERE idUsuario = '$idUsuario' GROUP BY idPalabraClave HAVING SUM(favorito + contador) >= $contadorS";
$querychecarSugeridos = $Conexion->query($sqlchecarSugeridos);
if(mysqli_num_rows($querychecarSugeridos) > 0){
    $sqlUpdate = "UPDATE algoritmos SET sugerido = '5', contador = '0' WHERE idEvento = $idBoda AND idUsuario = '$idUsuario' AND idPalabraClave IN ( SELECT idPalabraClave FROM algoritmos WHERE idUsuario = '$idUsuario' GROUP BY idPalabraClave HAVING SUM(favorito + contador) >= $contadorS)";
    $queryUpdate = $Conexion->query($sqlUpdate);
}

?>


