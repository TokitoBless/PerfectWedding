<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: agregarReunion.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

if (isset($_POST['nombreReunion']) && isset($_POST['temas']) && isset($_POST['fechaReunion']) && isset( $_POST['horaReunion']) && isset($_POST['linkZoom'])) {
  function validar($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
    $nombreReunion = validar($_POST['nombreReunion']);
    $temas = validar($_POST['temas']);
    $fechaReunion = validar($_POST['fechaReunion']);
    $horaReunion = validar($_POST['horaReunion']);
    $linkZoom = validar($_POST['linkZoom']);
    $invitados = isset($_POST['invitados']) ? $_POST['invitados'] : array();
    
    // Agregar al usuario que crea la reunión como invitado
    if (!in_array($idUsuario, $invitados)) {
        $invitados[] = $idUsuario;
    }

    // Convertir la lista de invitados en un formato serializable (ej. JSON o CSV)
    $invitados_serializados = json_encode($invitados);

    // SQL para insertar la reunión en la base de datos (estilo directo)
    $sqlAgregar = "INSERT INTO reuniones (idEvento, nombreReunion, temas, fecha, hora, invitados, link) 
                   VALUES ('$idBoda', '$nombreReunion', '$temas', '$fechaReunion', '$horaReunion', '$invitados_serializados', '$linkZoom')";

    // Ejecutar la consulta
    $queryAgregar = $Conexion->query($sqlAgregar);

    if ($queryAgregar) {
        // Redirigir al calendario después de guardar
        header('Location: calendario.php?idUsuario=' . $idUsuario . '&idBoda=' . $idBoda . '&success=Reunión guardada correctamente');
        exit();
    } else {
        echo "Error al guardar la reunión: " . $Conexion->error;
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
    <title>Nueva reunion</title>
    
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
                <a class="nav-item nav-link" href="tablaKanban.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tabla kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Tableros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="panelGeneral.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tablero general</a></li>
                            <li><a class="dropdown-item" href="tablerosFavoritos.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tableros favoritos</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $idUsuario; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<h3>Nueva reunion</h3>

    
<div class="container d-flex justify-content-center">
  <form action="agregarReunion.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>" method="POST" class="w-50">
    <div class="row mb-3">
      <!-- Nombre de la Reunión -->
      <div class="col-6 text-end">
        <label for="nombreReunion">Nombre de la Reunión</label>
      </div>
      <div class="col-6">
        <input type="text" id="nombreReunion" name="nombreReunion" class="form-control" minlength="15" required>
      </div>
    </div>

    <div class="row mb-3">
      <!-- Temas a Abordar -->
      <div class="col-6 text-end">
        <label for="temas">Temas a abordar</label>
      </div>
      <div class="col-6">
        <textarea id="temas" name="temas" class="form-control" minlength="50" maxlength="200"  required></textarea>
      </div>
    </div>

    <div class="row mb-3">
      <!-- Fecha de la Reunión -->
      <div class="col-6 text-end">
        <label for="fechaReunion">Fecha</label>
      </div>
      <div class="col-6">
        <input type="date" id="fechaReunion" name="fechaReunion" class="form-control" required>
      </div>
    </div>

    <div class="row mb-3">
      <!-- Hora de la Reunión -->
      <div class="col-6 text-end">
        <label for="horaReunion">Hora</label>
      </div>
      <div class="col-6">
        <input type="time" id="horaReunion" name="horaReunion" class="form-control" min="08:00" max="22:00" required>
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
              // Aquí obtendrás los invitados de la base de datos
              $sql = "SELECT id, nombreCompleto, invitadoDe FROM invitados WHERE idEvento = '$idBoda'";
              $resultado = $Conexion->query($sql);
              if ($resultado->num_rows > 0) {
                  while($row = $resultado->fetch_assoc()) {
                      echo "<option value='".$row['id']."'>".$row['nombreCompleto']." (".$row['invitadoDe'].")</option>";
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

    <div class="row mb-3">
      <!-- Link de Zoom -->
      <div class="col-6 text-end">
        <label for="linkZoom">Link de Zoom</label>
      </div>
      <div class="col-6">
        <input type="url" id="linkZoom" name="linkZoom" class="form-control" required>
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