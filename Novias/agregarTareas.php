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
    header('Location: agregarTareas.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['tituloTarea'];
    $tipoTarea = $_POST['tipoTarea'];
    $tareaPadre =  isset($_POST['tareaPadre']) ? $_POST['tareaPadre'] : '';
    $descripcion = $_POST['descripcion'];
    $encargado = $_POST['encargado'];
    $titulo = $_POST['tituloTarea'];
    $fecha = $_POST['fechaTarea'];
    $prioridad = $_POST['prioridad'];
    $fechaC = new DateTime();
    $fechaCreacion = $fechaC->format('Y-m-d'); 

    $sqlAgregar = "INSERT INTO tareas (idBoda, idUsuario, idTarea, titulo, descripcion, idEncargado, fecha, creacionTarea, prioridad, porcentaje, estatus, aprobado) 
                   VALUES ('$idBoda', '$idUsuario', '$tareaPadre', '$titulo', '$descripcion', '$encargado', '$fecha', '$fechaCreacion', '$prioridad', '0', 'Pendiente', '0')";

    $queryAgregar = $Conexion->query($sqlAgregar);

    //Enviar notificacion
    $fechaC = new DateTime();
    $fechaCreacion = $fechaC->format('Y-m-d'); 
    $detallesNotificacion = "Tienes una nueva tarea llamada ". $titulo .", la fecha limita para hacerla es ". $fecha ."";
    $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$encargado', 'Tienes una nueva tarea', '$fechaCreacion', '$detallesNotificacion' )";    
    $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

    $sqlInvitados= "SELECT usuario, correo from usuarios where id = '$encargado'";
    $queryInvitados = $Conexion->query($sqlInvitados);
    $row = $queryInvitados->fetch_assoc();
    $usuarioInvitado = $row['usuario'];
    $correoInvitado = $row['correo'];

    //Envio del correo
    $nombreEmpresa = 'PerfectWedding';
    $destino = 'dianapdz09@gmail.com'; //correo del cliente
    $asunto = 'Tienes una nueva tarea';

    $contenido = '
        <html> 
            <body> 
                <h2>¡Hola '.$usuarioInvitado.'! </h2>
                <p> 
                    Has sido asignado a una nueva tarea llamada  "' . $titulo . '". <br>
                    La fecha límite para completar esta tarea es '.$fecha.'. ¡Buena suerte!"
                </p> 
            </body>
        </html>
    ';
    //para el envío en formato HTML 
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=UTF8\r\n"; 

    //dirección del remitente
    $headers .= "FROM: $nombreEmpresa <$destino>\r\n";
    mail($destino,$asunto,$contenido,$headers);

    echo '<script>
        alert("Tarea creada");
        window.location.href = "tablaKanban.php?idUsuario=' . $idUsuario . '&idBoda=' . $idBoda . '";
        </script>';
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
    <title>Tabla kanban</title>
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

<h3>Tabla kanban</h3>    

<div style="width: 700px; padding-left: 50px;">
    <form action="agregarTareas.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>" method="POST">
    <!-- Titulo -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Título:</label>
            <div class="col-sm-9">
                <input type="text" id="tituloTarea" name="tituloTarea" required class="form-control" minlength="5" >
            </div>
        </div>
       
    <!-- Tipo de tarea -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Tipo:</label>
            <div class="col-sm-9">
                <select id="tipoTarea" name="tipoTarea" required class="form-control">
                    <option value="">Seleccione una opcion</option>
                    <option value="Tarea">Tarea</option>
                    <option value="Subtarea">Subtarea</option>
                </select>
            </div>
        </div>

    <!-- Nombre de la tarea padre -->
        <div id="tareaPadre" style="display: none;">    
            <div class="row mb-4">
                <label class="col-sm-3 col-form-label">Nombre de la tarea:</label>            
                <div class="col-sm-9">
                    <select id="tareaPadre" name="tareaPadre" class="form-control">
                    <?php
                        $sqlTarea = "SELECT id, titulo FROM tareas WHERE idboda = '$idBoda' AND idTarea = '0'";
                        $queryTarea = $Conexion->query($sqlTarea);
                        if ($queryTarea->num_rows > 0) {
                            echo "<option value='' disabled selected>Seleccione una opcion</option>";
                            while($row = $queryTarea->fetch_assoc()) {
                                echo "<option value='".$row['id']."'>".$row['titulo']."</option>";
                            }
                        }else {
                            echo "<option value='' disabled selected>No hay tareas crea una primero, por favor</option>";
                        }
                    ?>
                    </select>
                </div>
            </div>
        </div>

    <!-- Nombre del encargado -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Nombre Encargado:</label>
            <div class="col-sm-9">
            <select id="encargado" name="encargado" class="form-control" required>
                <?php
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
              $('#encargado').select2({
              placeholder: "Selecciona a un encargado",
              allowClear: true,
              });
          });
        </script> 
            </div>
        </div>

    <!-- Descripcion -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Descripción Tarea:</label>
            <div class="col-sm-9">
                <textarea id="descripcion" name="descripcion" required class="form-control" minlength="20"></textarea>
            </div>
        </div>
        
    <!-- Fecha -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Fecha limite:</label>
            <div class="col-sm-9">
                <input type="date" id="fechaTarea" name="fechaTarea" required class="form-control"  onchange="validarFecha()">
            </div>
        </div>

    <!-- Prioridad -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">Prioridad:</label>
            <div class="col-sm-9">
                <select id="prioridad" name="prioridad" required class="form-control">
                    <option value="">Seleccione una opcion</option>
                    <option value="1">Alta</option>
                    <option value="0">Baja</option>
                </select>
            </div>
        </div>

        <center>
            <button type="submit" class="btn btn-rosa">Agregar</button><br><br>
        </center>
        
    </form>
</div>
<script>
    document.getElementById('tipoTarea').addEventListener('change', function() {
        var tareaPadre = document.getElementById('tareaPadre');
        if (this.value === 'Subtarea') {
            tareaPadre.setAttribute('required', 'required');
            tareaPadre.style.display = 'block';
        } else {
            tareaPadre.removeAttribute('required');
            tareaPadre.style.display = 'none';
        }
    });

function validarFecha() {
    const fechaInput = document.getElementById('fechaTarea');
    fechaHoy = new Date();
    fechaHoy.setDate(fechaHoy.getDate() - 1); 
    const fechaSeleccionada = new Date(fechaInput.value);
    const fechaLimite = new Date('<?php echo $fechaBoda; ?>');
    const fecha10dias = new Date(fechaHoy); 
    fecha10dias.setDate(fecha10dias.getDate() + 10);

    if (!((fechaSeleccionada >= fechaHoy) && (fechaSeleccionada < fechaLimite))) {
        alert("La fecha seleccionada debe ser posterior a la actual y anterior a la fecha de la boda, favor de seleccionar otra.");
        fechaInput.value = '';
    }
    if (!((fechaSeleccionada >= fecha10dias))) {
        alert("La fecha seleccionada debe ser igual o superior a 10 días a partir de la fecha actual, favor de seleccionar otra.");
        fechaInput.value = '';
    }
}
</script>
</body>
</html>
