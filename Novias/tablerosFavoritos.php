<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];

    if (isset($_POST['nuevoTablero'])){
        $nuevoTablero = $_POST['nuevoTablero'];
        $sql = "INSERT INTO tablerospersonalizados (idUsuario, nombre) VALUES ('$idUsuario', '$nuevoTablero')";
        $Conexion->query($sql);
        echo '<script>
        alert("Nuevo tablero creado");
        window.location.href = "tablerosFavoritos.php?idUsuario=' . $idUsuario . '&idBoda=' . $idBoda . '";
        </script>';
        exit();
    }

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

<div style="display: flex; justify-content: flex-end;">
    <form class="form-inline" action="tablerosFavoritos.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>" method="POST">
        <div class="form-floating input-container">
            <input type="text" name="nuevoTablero" pattern="[a-zA-Z ]{2,254}" title="Solo se permiten letras"  class="form-control" id="floatingInput">
            <label for="floatingInput">Nombre del nuevo tablero</label>
        </div>    
        <button type="submit" class="btn btn-ch-marilla">Crear tablero</button>
    </form>
</div>
<hr>
    <div class="contenedor-botones ">
        <button class="boton" onclick="mostrarSubOpciones('tablerosGuardados')">Tableros Guardados</button>
        <button class="boton" onclick="mostrarSubOpciones('tablerosCompartidos')">Tableros Compartidos</button>
        <button class="boton" onclick="mostrarContenido('','serviciosCompartidos')">Servicios Compartidos</button>
    </div>
    <hr>
    <div id="usuariosTablero"></div>
    <div id="contenido" class="contenido-tablero"></div>
    <div id="contenido-tablero"></div>
    

    <!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container ">
                    <div class="row justify-content-start">
                        <div class="col-4">
                            <div class="carrusel-img-container">
                                <div id="carouselExample" class="carousel slide">
                                    <div class="carousel-inner" id="carouselImages">
                                        <!-- Imagenes javas -->
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <b>Descripción:</b><br> <span id="modalDescrip"></span><br>
                            <b>Precio:</b><br> $<span id="modalPrecio"></span><br>
                            <b>Nombre del proveedor:</b><br> <span id="modalNombreProveedor"></span><br>
                            <b id="modalLabelSitioWeb">SitioWeb:</b><br> <span id="modalSitioWeb" ></span>
                        </div>
                    </div>
                    <center>
                        <b>Calificación:</b>
                        <div class="star-container" id="starContainer">
                            <!-- Calificacioncita -->
                        </div>
                    </center>

                    <b>Reseñas: </b><span id="totalResenas"></span>
                    <div id="commentsList"></div>
                </div>
                
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" id="solicitarPresupuestoBtn" href="#" role="button">Cotización</a>
            </div>
        </div>
    </div>
</div>
<br><br><br>
</body>
</html>
<script>
    function mostrarSubOpciones(tipo) {
            fetch(`tablerosOpciones.php?tipo=${tipo}&idUsuario=<?php echo $idUsuario?>`)
                .then(response => response.json())
                .then(data => {
                    const tableroContainer = document.getElementById('contenido');
                    tableroContainer.innerHTML = '';
                    tableroContainer.style.textAlign = 'right';
                    const contenidoDiv = document.getElementById('contenido-tablero');
                    contenidoDiv.innerHTML = '';
                    const itemElement = document.getElementById('usuariosTablero');
                    itemElement.innerHTML = '';

                    data.forEach(tablero => {
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
                .then(response => response.json())
                .then(data => {
                    const tableroContainer = document.getElementById('contenido');
                    const contenidoDiv = document.getElementById('contenido-tablero');
                    const itemElement = document.getElementById('usuariosTablero');
                    tableroContainer.innerHTML = '';
                    contenidoDiv.innerHTML = '';
                    itemElement.innerHTML = '';
                    if(id!=''){
                        if(tipo=='tablerosCompartidos'){
                            itemElement.style.textAlign = 'right';
                            itemElement.innerHTML = `<p>Tablero compartido con: ${data[1]} y creado por ${data[2]}</p>`;
                        }
                        tableroContainer.style.textAlign = 'right';
                        tableroContainer.innerHTML = `<br><a type="button" href="tablerosCompartir.php?idTablero=${id}&idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda?>" class="btn btn-ch-marilla">Compartir tablero</a><br>`;
                        
                    }
                    
                   
                    contenidoDiv.innerHTML = data[0]; // Inserta el HTML recibido

                    if (data.length === 0) {
                        // Mensaje según la opción seleccionada si no hay resultados
                        let mensaje = tipo === 'tablerosGuardados' ? 'No se cuenta con Tableros Guardados.' :
                                      tipo === 'tablerosCompartidos' ? 'No se cuenta con Tableros Compartidos.' :
                                      tipo === 'serviciosGuardados' ? 'No se cuenta con Servicios Guardados.' :
                                      'No se cuenta con Servicios Compartidos.';
                        contenidoDiv.innerHTML = `<p>${mensaje}</p>`;
                    } else {
                    }
                });
        }

        function cargarContenido(respuesta, tipo) {
            const contenidoDiv = document.getElementById('contenido-tablero');
            //contenidoDiv.innerHTML = `<h2>Contenido Seleccionado: ${nombre}</h2><p>Mostrando información de ${tipo.includes('tableros') ? 'Tablero' : 'Servicio'}.</p>`;
            //contenidoDiv.innerHTML = `${respuesta}`;
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('serviceModal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            console.log(id);
            var idBoda = button.getAttribute('data-idBoda');
            var idUsuario = button.getAttribute('data-idUsuario');
            var images = JSON.parse(button.getAttribute('data-images'));
            var name = button.getAttribute('data-name');
            var descrip = button.getAttribute('data-descrip');
            var categoria = button.getAttribute('data-categoria');
            var precio = button.getAttribute('data-precio');
            var calificacion = button.getAttribute('data-cali');
            var palabraClave = button.getAttribute('data-palabraClave');
            var nombreProveedor = button.getAttribute('data-nombreProveedor');
            var sitioWeb = button.getAttribute('data-sitioWeb');

            var carouselImages = modal.querySelector('#carouselImages');            
            var modalName = modal.querySelector('#modalName');
            var modalDescrip = modal.querySelector('#modalDescrip');
            var modalPrecio = modal.querySelector('#modalPrecio');
            var solicitarPresupuestoBtn = modal.querySelector('#solicitarPresupuestoBtn');
            var modalNombreProveedor = modal.querySelector('#modalNombreProveedor');
            var modalSitioWeb = modal.querySelector('#modalSitioWeb');
            var modalLabelSitioWeb = modal.querySelector('#modalLabelSitioWeb');
            var starContainer = modal.querySelector('#starContainer');
            

            // Limpiar el contenido anterior
            carouselImages.innerHTML = '';
            starContainer.innerHTML = '';

            images.forEach(function (image, index) {
                var activeClass = index === 0 ? 'active' : '';
                var carouselItem = `
                <div class="carousel-item ${activeClass}">
                    <img src="${image}" class="d-block w-100" alt="Image ${index + 1}">
                </div>`;
                carouselImages.insertAdjacentHTML('beforeend', carouselItem);
            });

            //Crear elementos de la calificacion
            for (var i = 1; i <= 5; i++) {
                var starImg = document.createElement('img');
                starImg.src = i <= calificacion ? '../Imagenes/estrella_completa.png' : '../Imagenes/estrella_vacia.png';
                starImg.alt = 'Estrella';
                starContainer.appendChild(starImg);
            }


            modalName.textContent = name;
            modalDescrip.textContent = descrip;
            modalPrecio.textContent = precio;
            modalNombreProveedor.textContent = nombreProveedor;
            solicitarPresupuestoBtn.href = `solicitarPresupuesto.php?idBoda=${idBoda}&idUsuario=${idUsuario}&idServicio=${id}`;
            
            if (!sitioWeb || sitioWeb.trim() === "") {
                modalSitioWeb.textContent = "No hay sitio web";
            } else {
                modalSitioWeb.textContent = sitioWeb;
            }

            
            // Cargar y mostrar los comentarios
            fetch(`mostrarResenas.php?idServicio=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(comentarios => {
                console.log(comentarios);
                const commentsList = document.getElementById('commentsList');
                const totalResenas = document.getElementById('totalResenas');
                commentsList.innerHTML = '';
                comentarios.forEach(comentario => {
                    totalResenas.textContent = comentario.totalResenas;
                    const usuarioDiv = document.createElement('div');
                    const commentDiv = document.createElement('div');
                    commentDiv.className = 'comment';
                    const starContainerResena = document.createElement('div');
                    starContainerResena.className = 'starContainerResena';
                    usuarioDiv.innerHTML = `
                        <span>${comentario.usuario}</span>
                    `;
                    commentDiv.innerHTML = `
                        <p>${comentario.resena}</p>
                        <hr>
                    `;

                    
                    usuarioDiv.appendChild(starContainerResena);
                    usuarioDiv.appendChild(commentDiv);
                    commentsList.appendChild(usuarioDiv);
                    //Crear elementos de la calificacion
                for (var i = 1; i <= 5; i++) {
                    var starImg = document.createElement('img');
                    starImg.src = i <= comentario.calificacion ? '../Imagenes/estrella_completa.png' : '../Imagenes/estrella_vacia.png';
                    starImg.alt = 'Estrella';
                    starContainerResena.appendChild(starImg);
                }
                });

            });

        });
    });
</script>