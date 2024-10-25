<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    $sqlfechaBoda= "SELECT fechaBoda from bodas where usuario = '$idUsuario' and idEvento = '$idBoda'";
    $queryFechaBoda = $Conexion->query($sqlfechaBoda);
    $row = $queryFechaBoda->fetch_assoc();
    $fechaBoda = $row['fechaBoda'];
} else {
    header('Location: agregarEvento.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

if (isset($_POST['nombreEvento']) && isset($_POST['descripcion']) && isset($_POST['fecha']) && isset($_POST['hora']) && isset($_POST['duracion'])) {
    function validar($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $nombreEvento = validar($_POST['nombreEvento']);
    $descripcion = validar($_POST['descripcion']);
    $fecha = validar($_POST['fecha']);
    $hora = validar($_POST['hora']);
    $duracion = validar($_POST['duracion']);
    $invitados = isset($_POST['invitados']) ? $_POST['invitados'] : array();

    // Agregar al usuario que crea la reunión como invitado
    if (!in_array($idUsuario, $invitados)) {
        $invitados[] = $idUsuario;
    }

    
    $invitados_serializados = json_encode($invitados);

    $sqlAgregar = "INSERT INTO eventos (idEvento, nombreEvento, descripcion, fecha, hora, duracion, invitados) VALUES ('$idBoda', '$nombreEvento', '$descripcion', '$fecha', '$hora', '$duracion', '$invitados_serializados')";
    $queryAgregar = $Conexion->query($sqlAgregar);

    $detallesNotificacion = "Has sido invitdo al evento ". $nombreEvento .". La fecha y hora del evento es ". $fecha ." a las ". $hora .".";
    $fecha = new DateTime();
    $fechaCreacion = $fecha->format('Y-m-d'); 

    if ($queryAgregar) {
        $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idUsuario', 'Nuevo evento', '$fechaCreacion', '$detallesNotificacion' )";    
        $queryGuardarElementoNotificacion = $Conexion->query($sqlGuardarNotificacion);
        header('Location: calendario.php?idUsuario=' . $idUsuario . '&idBoda=' . $idBoda . '&success=Reunión guardada correctamente');
        exit();
    } else {
        echo "Error al guardar el evento: " . $Conexion->error;
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

    <link rel="stylesheet" type="text/css" href="stylesCalendario.css">
    <title>Nuevo evento</title>
    
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
<h3>Nuevo Evento</h3>

<div class="container d-flex justify-content-center">
    <form action="agregarEvento.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>" method="POST" class="w-50">
        <div class="row mb-3">
            <!-- Nombre de evento -->
            <div class="col-6 text-end">
                <label for="nombreEvento">Nombre de evento</label>
            </div>
            <div class="col-6">
                <input type="text" id="nombreEvento" name="nombreEvento" class="form-control" minlength="15" required>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Descripción -->
            <div class="col-6 text-end">
                <label for="descripcion">Descripción</label>
            </div>
            <div class="col-6">
                <textarea id="descripcion" name="descripcion" class="form-control" minlength="50" maxlength="200" required></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Fecha -->
            <div class="col-6 text-end">
                <label for="fecha">Fecha</label>
            </div>
            <div class="col-6">
                <input type="date" id="fecha" name="fecha" class="form-control" required onchange="validarFecha()">
            </div>
        </div>

        <div class="row mb-3">
            <!-- Hora -->
            <div class="col-6 text-end">
                <label for="hora">Hora</label>
            </div>
            <div class="col-6">
                <input type="time" id="hora" name="hora" class="form-control" min="08:00" max="22:00" required>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Duración -->
            <div class="col-6 text-end">
                <label for="duracion">Duración</label>
            </div>
            <div class="col-6">
                <select id="duracion" name="duracion" class="form-control" required>
                    <option value="1">1 hora</option>
                    <option value="2">2 horas</option>
                    <option value="3">3 horas</option>
                    <option value="4">4 horas</option>
                    <option value="5">5 horas</option>
                    <option value="6">6 horas</option>
                    <option value="7">7 horas</option>
                    <option value="8">8 horas</option>
                    <option value="9">9 horas</option>
                    <option value="10">10 horas</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Invitados -->
            <div class="col-6 text-end">
                <label for="invitados">Invitados</label>
            </div>
            <div class="col-6">
                <select id="invitados" name="invitados[]" class="form-control" multiple="multiple" required>
                        <?php
                        // Agarrar los invitados
                        $sqlIdAyudante = "SELECT idUsuario FROM ayudantes WHERE idEvento = '$idBoda'";
                        $queryIdAyudante = $Conexion->query($sqlIdAyudante);

                        if ($queryIdAyudante->num_rows > 0) {
                            while($row = $queryIdAyudante->fetch_assoc()) {
                                $idAyudante = $row['idUsuario'];

                                $sqlNombreAyudante = "SELECT id, usuario, correo FROM usuarios WHERE id = '$idAyudante'";
                                $queryNombreAyudante = $Conexion->query($sqlNombreAyudante);
                                $rowNombre = $queryNombreAyudante->fetch_assoc();
                                echo "<option value='".$rowNombre['id']."'>".$rowNombre['usuario']." (".$rowNombre['correo'].")</option>";
                            }
                        }
                        ?>
                </select>

                <script>
                $(document).ready(function() {
                    $('#invitados').select2({
                        placeholder: "Selecciona los invitados",
                        allowClear: true,
                    });
                });
                </script> 
            </div>
        </div>

        <!-- Botón Guardar -->
        <div class="row">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-rosa">Guardar</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>

<script>
function validarFecha() {
    const fechaInput = document.getElementById('fecha');
    fechaHoy = new Date();
    fechaHoy.setDate(fechaHoy.getDate() - 1); 
    const fechaSeleccionada = new Date(fechaInput.value);
    const fechaLimite = new Date('<?php echo $fechaBoda; ?>');
    if (!((fechaSeleccionada >= fechaHoy) && (fechaSeleccionada < fechaLimite))) {
        alert("La fecha seleccionada debe ser posterior a la actual y anterior a la fecha de la boda, favor de seleccionar otra.");
        fechaInput.value = '';
    }
}
</script>
