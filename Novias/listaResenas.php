<?php
include_once('../Conexion/conexion.php');


if(isset($_GET['idBoda']) && isset($_GET['idUsuario'])) {
    $idBoda =  base64_decode($_GET['idBoda']);
    $idUsuario =  base64_decode($_GET['idUsuario']);
    
    $sqlCotizaciones = "SELECT c.*, s.nombreServicio, p.nombreEmpresa FROM cotizaciones c
            JOIN servicios s ON c.idServicio = s.id
            JOIN proveedores p ON s.proveedor = p.id
            WHERE c.idEvento = $idBoda AND c.idUsuario = $idUsuario";
    $queryCotizaciones =  $Conexion->query($sqlCotizaciones);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stylesResena.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de reseñas</title>
</head>
<body>
<nav class="navbar bg-body-tertiary">
<div class="container-fluid">
    <p class="title_nav">
    <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
    Perfect Wedding
    </p>
</div>
</nav>
<br>
<h2>Lista de servicios</h2>
<br>
    <ul>
        <?php while($row = $queryCotizaciones->fetch_assoc()): ?>
            <li>
                <a href="escribirResena.php?idUsuario=<?php echo urlencode($row['idUsuario']); ?>&idServicio=<?php echo urlencode($row['idServicio']); ?>&idBoda=<?php echo urlencode($row['idEvento']); ?>">
                    <?php echo htmlspecialchars($row['nombreServicio']); ?> (<?php echo htmlspecialchars($row['nombreEmpresa']); ?>)
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>