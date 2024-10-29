<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idTablero']) && isset($_GET['idBoda'])&&isset($_GET['idUsuario']) ) {
    $idBoda = $_GET['idBoda'];
    $idUsuario = $_GET['idUsuario'];
    $idTablero = $_GET['idTablero'];

    //Nombre del tablero
    $sqlTablero = "SELECT nombre FROM tablerospersonalizados WHERE id = $idTablero";
    $queryTablero =  $Conexion->query($sqlTablero);
    $row = $queryTablero->fetch_assoc();
    $nombreTablero = $row['nombre'];

    if (isset($_POST['invitados'])){
        $invitados = $_POST['invitados'];
        $fechaC = new DateTime();
        $fechaCreacion = $fechaC->format('Y-m-d'); 
        $detallesNotificacion = "Un tablero ha sido compartido contigo";

        foreach ($invitados as $invitado){
            $sql = "INSERT INTO tableroscompartidos (idTablero, idUsuario) VALUES ('$idTablero', '$invitado')";
            $Conexion->query($sql);
      
            $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$invitado', 'Tablero compartido', '$fechaCreacion', '$detallesNotificacion' )";    
            $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

        }
        echo '<script>
                alert("Tablero compartido con exito");
                window.location.href = "tablerosFavoritos.php?idUsuario=' . $idUsuario . '&idBoda=' . $idBoda . '";
            </script>';
            exit();
    }

} else {
    header('Location: compartir.php?error="No se proporcionó IDs"');
    exit();
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
    <title>Compartir servicio</title>
    
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
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown1">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Mensajes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="notificaciones.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Notificaciones</a></li>
                            <li><a class="dropdown-item" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?> &ind=I">Mensajes</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
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
<h3>Compartir tablero "<?php echo htmlspecialchars($nombreTablero); ?>"</h3>
<br>
    
<div class="container d-flex justify-content-center">
  <form action="tablerosCompartir.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&idTablero=<?php echo $idTablero; ?>" method="POST" class="w-50">


    <div class="row">
        <div class="col-12 text-center">
            <label>Nombre de la persona a compartir</label>
            <br><br>
            <select id="invitados" name="invitados[]" class="form-control" multiple="multiple" required>
                    <?php
                    // Agarrar los invitados
                    $sqlIdAyudante = "SELECT idUsuario FROM ayudantes WHERE idEvento = '$idBoda'";
                    $queryIdAyudante = $Conexion->query($sqlIdAyudante);

                    if ($queryIdAyudante->num_rows > 0) {
                        while($row = $queryIdAyudante->fetch_assoc()) {
                            $idAyudante = $row['idUsuario'];

                            $sqlNombreAyudante = "SELECT id, usuario FROM usuarios WHERE id = '$idAyudante'";
                            $queryNombreAyudante = $Conexion->query($sqlNombreAyudante);
                            $rowNombre = $queryNombreAyudante->fetch_assoc();
                            echo "<option value='".$rowNombre['id']."'>".$rowNombre['usuario']."</option>";
                        }
                    }
                    ?>
            </select>

                <script>
                $(document).ready(function() {
                    $('#invitados').select2({
                        placeholder: "Selecciona con quien compartir",
                        allowClear: true,
                    });
                });
                </script> 
            <br><br>
            <button type="submit" class="btn btn-rosa">Guardar</button>
        </div>
    </div>
  </form>
</div>

</body>
</html>