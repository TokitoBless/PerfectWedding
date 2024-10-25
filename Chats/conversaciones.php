<?php
include_once('../Conexion/conexion.php');

$ind = isset($_GET['ind']) ? strtolower($_GET['ind']) : '';
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
$idServicio = isset($_GET['idServicio']) ? $_GET['idServicio'] : '';
$idBoda = isset($_GET['idBoda']) ? $_GET['idBoda'] : ''; 

if ($idUsuario) {
    if($ind == 'i'){
        $sqlNombre = "SELECT p.nombreEmpresa AS nombre from servicios s
        join proveedores p on p.id = s.proveedor
        where s.id = $idUsuario";
    }else{
        $sqlNombre = "SELECT CONCAT(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombre from usuarios
        where id = $idUsuario";
    }
    $queryNombres = $Conexion->query($sqlNombre);
    $row = $queryNombres->fetch_assoc();

    $sqlMensajes = "SELECT * FROM mensajes WHERE idServicio = ? AND idUsuario = ? ORDER BY fecha ASC";
    $stmt = $Conexion->prepare($sqlMensajes);
    $stmt->bind_param("ss", $idServicio, $idUsuario);
    $stmt->execute();
    $resultMensajes = $stmt->get_result();
}

// Función para insertar un nuevo mensaje en la BD
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $fechayHoraMensaje = date("Y-m-d H:i:s");
    $sqlInsert = "INSERT INTO mensajes (idBoda, idServicio, idUsuario, mensaje, fecha, remitente) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $Conexion->prepare($sqlInsert);
    $stmtInsert->bind_param("isssss", $idBoda, $idServicio, $idUsuario, $mensaje, $fechayHoraMensaje, $ind);
    $stmtInsert->execute();

    // Refrescar la página para mostrar el nuevo mensaje
    header("Location: conversaciones.php?idUsuario=" . urlencode($idUsuario)."&idServicio=".urlencode($idServicio)  . "&idBoda=" . urlencode($idBoda) . "&ind=". urlencode($ind));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Chat con <?php echo htmlspecialchars($row['nombre']); ?></title>
    <link rel="stylesheet" type="text/css" href="stylesMensajes.css">
    
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
                <a class="nav-item nav-link" href="../Proveedor/panelServicios.php?id=<?php echo $idUsuario; ?>">Servicios</a>
                <a class="nav-item nav-link" href="../Proveedor/listaMensajes.php?idUsuario=<?php echo $idUsuario; ?>&ind=p">Conversaciones</a>
                <a class="navbar-brand" href="../Proveedor/infoPerfil.php?id=<?php echo $idUsuario; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
    <a  onclick="window.history.back()">
        <img src="../Imagenes/antes.png" alt="Anterior" width="50" height="50">
    </a>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Chat con <?php echo htmlspecialchars($row['nombre']); ?></h2>
        </div>
        <div class="chat-body">

            <?php while($row = $resultMensajes->fetch_assoc()): ?>
                <div class="message <?php echo $row['remitente'] == $ind ? 'sent' : 'received'; ?>">
                    <div class="content">
                        <p><?php echo htmlspecialchars($row['mensaje']); ?></p>
                        <small><?php echo htmlspecialchars($row['fecha']); ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="chat-footer">
            <form method="POST" action="conversaciones.php?idUsuario=<?php echo urlencode($idUsuario); ?>&idServicio=<?php echo urlencode($idServicio); ?>&idBoda=<?php echo urlencode($idBoda); ?>&ind=<?php echo urlencode($ind) ?>">
                <input type="text" name="mensaje" placeholder="Escribe un mensaje..." required>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
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
