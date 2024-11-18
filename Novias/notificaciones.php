<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuarioEncriptado = $_GET['idUsuario'];
    $idUsuario = base64_decode($idUsuarioEncriptado);
    $idBodaEncriptado = $_GET['idBoda'];
    $idBoda = base64_decode($idBodaEncriptado);
} else {
    header('Location: notificaciones.php?error="No se proporcionó ID de usuario ni de boda"');
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Notificaciones</title>
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
<h3>Notificaciones</h3>
<br>
<div class="container mt-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre de la notificacion</th>
                <th>Detalles</th>
                <th>Fecha de envio</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sqlNotificaciones = "SELECT * FROM notificaciones WHERE idEvento = '$idBoda' AND	idUsuario = '$idUsuario' ORDER BY fecha DESC";
            $queryNotificaciones = $Conexion->query($sqlNotificaciones);

            if ($queryNotificaciones->num_rows > 0) {
                while ($row = $queryNotificaciones->fetch_assoc()) {
                    echo "<tr>";
                    //Nombre de la notificacione
                    echo "<td>{$row['notificacion']}</td>";
                    
                    // Detalles 
                    echo "<td>{$row['detalles']}</td>";
                    //Fecha de envio de la notificacion
                    echo "<td>{$row['fecha']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay notificaciones registradas</td></tr>";
            }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
