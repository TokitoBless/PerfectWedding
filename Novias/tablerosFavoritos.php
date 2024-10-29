<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: notificaciones.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}   
// Consulta de tableros
function obtenerTableros($tipo) {
    global $Conexion;
    switch ($tipo) {
        case 'tablerosGuardados':
            $consulta = "SELECT nombreTablero FROM tablerosGuardados";
            break;
        case 'tablerosCompartidos':
            $consulta = "SELECT nombreTablero FROM tablerosCompartidos";
            break;
        case 'serviciosGuardados':
            $consulta = "SELECT nombreServicio FROM serviciosGuardados";
            break;
        case 'serviciosCompartidos':
            $consulta = "SELECT nombreServicio FROM serviciosCompartidos";
            break;
        default:
            return [];
    }
    $resultado = $Conexion->query($consulta);
    return $resultado->fetch_assoc();
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="stylesPanel.css">
    <title>Tableros</title>
    
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
<h3>Tableros Favoritos</h3>
    <div class="contenedor-botones">
        <button class="boton" onclick="mostrarSubOpciones('tablerosGuardados')">Tableros Guardados</button>
        <button class="boton" onclick="mostrarSubOpciones('tablerosCompartidos')">Tableros Compartidos</button>
        <button class="boton" onclick="mostrarContenido('','serviciosCompartidos')">Servicios Compartidos</button>
    </div>
    
    <div id="contenido" class="contenido-tablero"></div>
    <div id="contenido-tablero"></div>


</body>
</html>
<script>
    function mostrarSubOpciones(tipo) {
            fetch(`tablerosOpciones.php?tipo=${tipo}&idUsuario=<?php echo $idUsuario?>`)
                .then(response => response.json())
                .then(data => {
                    const tableroContainer = document.getElementById('contenido');
                    tableroContainer.innerHTML = '';

                    data.forEach(tablero => {
                        console.log(tablero);
                        const tableroElement = document.createElement('div');
                        tableroElement.className = 'boton';
                        tableroElement.innerText = tablero.nombre;
                        tableroElement.onclick = () => mostrarContenido(tablero.id, tipo);
                        tableroContainer.appendChild(tableroElement);
                    });
                });
        }

        function mostrarContenido(id, tipo) {
            fetch(`tablerosInfo.php?tipo=${tipo}&idTablero=${id}&idUsuario=<?php echo $idUsuario?>&idBoda=<?php echo $idBoda?>`)
                .then(response => response.text())
                .then(data => {
                    const contenidoDiva = document.getElementById('contenido');
                    contenidoDiva.innerHTML = '';
                    const contenidoDiv = document.getElementById('contenido-tablero');
                    contenidoDiv.innerHTML = data; // Inserta el HTML recibido

                    if (data.length === 0) {
                        // Mensaje según la opción seleccionada si no hay resultados
                        let mensaje = tipo === 'tablerosGuardados' ? 'No se cuenta con Tableros Guardados.' :
                                      tipo === 'tablerosCompartidos' ? 'No se cuenta con Tableros Compartidos.' :
                                      tipo === 'serviciosGuardados' ? 'No se cuenta con Servicios Guardados.' :
                                      'No se cuenta con Servicios Compartidos.';
                        contenidoDiv.innerHTML = `<p>${mensaje}</p>`;
                    } else {
                        /*data.forEach(item => {
                            const itemElement = document.createElement('div');
                            itemElement.className = 'boton';
                            itemElement.innerText = tipo.includes('tableros') ? item.nombre : item.nombreServicio;
                            itemElement.onclick = () => cargarContenido(item, tipo);
                            contenidoDiv.appendChild(itemElement);
                        });*/
                    }
                });
        }

        function cargarContenido(respuesta, tipo) {
            const contenidoDiv = document.getElementById('contenido-tablero');
            //contenidoDiv.innerHTML = `<h2>Contenido Seleccionado: ${nombre}</h2><p>Mostrando información de ${tipo.includes('tableros') ? 'Tablero' : 'Servicio'}.</p>`;
            //contenidoDiv.innerHTML = `${respuesta}`;
        }
    </script>