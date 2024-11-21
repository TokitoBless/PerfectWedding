<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda']) && isset($_GET['idServicio'])) {
    $idUsuarioEncriptado = $_GET['idUsuario'];
    $idUsuario = base64_decode($idUsuarioEncriptado);
    $idBodaEncriptado = $_GET['idBoda'];
    $idBoda = base64_decode($idBodaEncriptado);
    $idServicioEncriptado = $_GET['idServicio'];
    $idServicio = base64_decode($idServicioEncriptado);

    //Nombre del servicio
    $sqlServicio = "SELECT nombreServicio FROM servicios WHERE id = $idServicio";
    $queryServicio =  $Conexion->query($sqlServicio);
    $row = $queryServicio->fetch_assoc();
    $nombreServicio = $row['nombreServicio'];

    if (isset($_POST['nuevoTablero'])){
        $nuevoTablero = $_POST['nuevoTablero'];
        $sql = "INSERT INTO tablerospersonalizados (idUsuario, nombre) VALUES ('$idUsuario', '$nuevoTablero')";
        $Conexion->query($sql);
        header('location:guardarServicioCard.php?idUsuario=' . $idUsuarioEncriptado . '&idBoda=' . $idBodaEncriptado . '&idServicio=' . $idServicioEncriptado . '');
        exit();
    }
    if (isset($_POST['tableros'])){
        $tableros = $_POST['tableros'];
        foreach ($tableros as $tablero){
            $sql = "INSERT INTO serviciosguardados (idUsuario, idBoda, idTablero, idServicio) VALUES ('$idUsuario', '$idBoda', '$tablero', '$idServicio')";
            $Conexion->query($sql);
            header('location:panelGeneral.php?success=Servicio Guardado&idUsuario=' . $idUsuarioEncriptado . '&idBoda=' . $idBodaEncriptado . '');
            exit();
        }
    }


} else {
    header('Location: guardarServicioCard.php?error="No se proporcionó IDs"');
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
    <title>Guardar servicio</title>
    
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
                <a class="nav-item nav-link" href="calendario.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Calendario</a>
                <a class="nav-item nav-link" href="tablaKanban.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tabla Kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown1">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Mensajes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="notificaciones.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Notificaciones</a></li>
                            <li><a class="dropdown-item" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?> &ind=I">Mensajes</a></li>
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
                            <li><a class="dropdown-item" href="panelGeneral.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tablero general</a></li>
                            <li><a class="dropdown-item" href="tablerosFavoritos.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tableros favoritos</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<h3>Guardar servicio <?php echo htmlspecialchars($nombreServicio); ?></h3>
<br>
<form class="form-inline" action="guardarServicioCard.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>&idServicio=<?php echo $idServicioEncriptado; ?>" method="POST">
    <div class="form-floating input-container">
        <input type="text" name="nuevoTablero" pattern="[a-zA-Z ]{2,254}" title="Solo se permiten letras"  class="form-control" id="floatingInput">
        <label for="floatingInput">Nombre del nuevo tablero</label>
    </div>    
    <button type="submit" class="btn btn-lila">Crear tablero</button>
</form>
    
<div class="container d-flex justify-content-center">
  <form action="guardarServicioCard.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>&idServicio=<?php echo $idServicioEncriptado; ?>" method="POST" class="w-50">

    <div class="row">
        <div class="col-12 text-center">
            <label>Nombre Tablero</label>
            <br><br>
            <select id="tableros" name="tableros[]" class="form-control" multiple="multiple" required>
                    <?php
                    // Agarrar los tableros
                    $sqltableros = "SELECT * FROM tablerospersonalizados WHERE idUsuario = '$idUsuario'";
                    $querytableros = $Conexion->query($sqltableros);

                    if ($querytableros->num_rows > 0) {
                        while($row = $querytableros->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['nombre']."</option>";
                        }
                    }
                    ?>
            </select>

                <script>
                $(document).ready(function() {
                    $('#tableros').select2({
                        placeholder: "Selecciona en que tablero guardar",
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