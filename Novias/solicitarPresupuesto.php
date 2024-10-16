<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])&& isset($_GET['idServicio'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    $idServicio = $_GET['idServicio'];

    //Fecha de la boda
    $sqlBoda = "SELECT fechaBoda FROM bodas WHERE idEvento = $idBoda";
    $queryBoda =  $Conexion->query($sqlBoda);
    $row = $queryBoda->fetch_assoc();
    $fecha = $row['fechaBoda'];
    $fechaBoda = date('Y-m-d', strtotime($fecha));

    //Nombre del servicio
    $sqlServicio = "SELECT nombreServicio FROM servicios WHERE id = $idServicio";
    $queryServicio =  $Conexion->query($sqlServicio);
    $row = $queryServicio->fetch_assoc();
    $nombreServicio = $row['nombreServicio'];

} else {
    header('Location: solicitarPresupuesto.php?error="No se proporcionó IDs"');
    exit();
}

if (isset($_POST['fechaBoda']) && isset($_POST['detalles'])) {
  function validar($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
    $detalles = validar($_POST['detalles']);
    
   
    $sqlAgregar = "INSERT INTO reuniones (idEvento, nombreReunion, temas, fecha, hora, invitados, link) 
                   VALUES ('$idBoda', '$nombreReunion', '$temas', '$fechaReunion', '$horaReunion', '$invitados_serializados', '$linkZoom')";

    $queryAgregar = $Conexion->query($sqlAgregar);

    if ($queryAgregar) {
        
    } else {
        echo "Error al guardar la solicitud: " . $Conexion->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Solicitar cotización</title>
    
</head>
<body>

<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="title_nav">
            <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
            <span>Perfect Wedding</span>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="calendario.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Calendario</a>
                <a class="nav-item nav-link" href="tablaKanban.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tabla Kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Lista invitados</a>
                <a class="nav-item nav-link" href="notificaciones.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Notificaciones</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Tableros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="panelGeneral.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tablero general</a></li>
                            <li><a class="dropdown-item" href="tablerosFavoritos.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tableros favoritos</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?idUsuario=<?php echo $idUsuario; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<h3>Solicitar cotización del servicio <?php echo htmlspecialchars($nombreServicio); ?></h3>
<br>
    
<div class="container d-flex justify-content-center">
  <form action="solicitarPresupuesto.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>" method="POST" class="w-50">


    <div class="row">
      <div class="col-12 text-center">
        <label for="fechaBoda">Fecha de  la boda</label>
        <input type="text" id="fechaBoda" name="fechaBoda" class="form-control" value="<?php echo htmlspecialchars($fechaBoda); ?>" readonly>
        <br><br>
        <label>Detalles de la solicitud</label>
        <textarea id="detalles" name="detalles" class="form-control" required></textarea>
        <br>
        <button type="submit" class="btn btn-rosa">Guardar</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>