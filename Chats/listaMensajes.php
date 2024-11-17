<?php
include_once('../Conexion/conexion.php');
include_once('../Novias/validacionesUsuarios.php');

if (isset($_GET['idUsuario']) && isset($_GET['ind'])) {
    $idUsuarioEncriptado = $_GET['idUsuario'];
    $idUsuario = base64_decode($idUsuarioEncriptado);
    $idBodaEncriptado = isset($_GET['idBoda']) ? $_GET['idBoda'] : '';
    $idBoda = base64_decode($idBodaEncriptado);
    $ind = strtolower($_GET['ind']);
} else {
    header('Location: listaMensajes.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

if($ind=='i'){
    $sqlDestinatarios = "SELECT DISTINCT m.idUsuario, m.idServicio, m.idBoda, p.nombreEmpresa AS nombre from mensajes m
    join servicios s on s.id = m.idServicio
    join proveedores p on p.id = s.proveedor
    where m.idUsuario = '$idUsuario'";
}
else{
    $sqlDestinatarios = "SELECT DISTINCT m.idUsuario, m.idServicio, m.idBoda, CONCAT(u.nombre, ' ', u.apellidoPaterno, ' ', u.apellidoMaterno) AS nombre from mensajes m
    join usuarios u on u.id = m.idUsuario
    where idServicio in (
    select id from servicios s where s.proveedor = '$idUsuario'
    )";
}
$queryDestinatarios = $Conexion->query($sqlDestinatarios);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stylesMensajes.css">
    <title>Lista de Destinatarios</title>

</head>
<body>
<!-- Nav Novias -->
<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary Ocultar">
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
                <a class="nav-item nav-link" href="../Novias/calendario.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Calendario</a>
                <a class="nav-item nav-link" href="../Novias/tablaKanban.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tabla Kanban</a>
                <a class="nav-item nav-link" href="../Novias/invitados.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown1">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Mensajes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../Novias/notificaciones.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Notificaciones</a></li>
                            <li><a class="dropdown-item" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&ind=i">Mensajes</a></li>
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
                            <li><a class="dropdown-item" href="../Novias/panelGeneral.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tablero general</a></li>
                            <li><a class="dropdown-item" href="../Novias/tablerosFavoritos.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Tableros favoritos</a></li>
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
<!-- Nav Proveedores -->
<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary OcultarP">
    <div class="container-fluid">
        <div class="title_nav">
            <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
            <span>Perfect Wedding</span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="../Proveedor/panelServicios.php?id=<?php echo $idUsuarioEncriptado; ?>">Servicios</a>
                <a class="nav-item nav-link" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&ind=p">Conversaciones</a>
                <a class="navbar-brand" href="../Proveedor/infoPerfil.php?id=<?php echo $idUsuarioEncriptado; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
    <h1>Mensaje Cotizaciones</h1>
    <br>
    <ul>
        <?php while($row = $queryDestinatarios->fetch_assoc()): ?>
            <li>
                <a href="conversaciones.php?idUsuario=<?php echo base64_encode($row['idUsuario']); ?>&idServicio=<?php echo base64_encode($row['idServicio']); ?>&idBoda=<?php echo base64_encode($row['idBoda']); ?>&ind=<?php echo urlencode($ind) ?>&idProveedor=<?php echo base64_encode($idUsuario);?>">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

<script>
    //Ocultar para ayudantes 
    document.addEventListener("DOMContentLoaded", function() {
        mostrarDiv = "<?php echo $ind ?>".toLowerCase();
        mostrarDiv = mostrarDiv == 'i' ? false : true;
        const elements = document.querySelectorAll('.Ocultar');

        elements.forEach(function(element) {
            element.classList.toggle('hidden', mostrarDiv); 
        });
        const elementsP = document.querySelectorAll('.OcultarP');

        elementsP.forEach(function(element) {
            element.classList.toggle('hidden', !mostrarDiv); 
        });
    });
</script>