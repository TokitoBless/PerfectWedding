<?php

include_once('./validacionesUsuarios.php');
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: panelGeneral.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}
$sqlTarea= "SELECT * from tareas where idEncargado = '$idUsuario' AND porcentaje < 100 ";
$queryTarea = $Conexion->query($sqlTarea);

if ($queryTarea->num_rows > 0) {
    while($row = $queryTarea->fetch_assoc()) {
        $titulo = $row['titulo'];
        $fecha = $row['fecha'];
        $creacionTarea = $row['creacionTarea'];
        $porcentaje = $row['porcentaje'];
        $encargado = $row['idEncargado'];
        
        $idNovia = $row['idUsuario'];

        $fechaC = new DateTime($fecha);
        $fechaCreacion = $fechaC->format('Y-m-d'); 
        $fechaC->modify('+2 days');
        $fecha2Dias = $fechaC->format('Y-m-d');

        $fechaActual = new DateTime(); 
        $fechaActual->modify('+ 1 week');
        $fechaActual1Semana = $fechaActual->format('Y-m-d');

        if($fecha2Dias > $creacionTarea){//Cada dos dias
            //Enviar notificacion
            $detallesNotificacion = "Este es un recordatorio de que la tarea " . $titulo . " tiene como fecha límite " . $fecha . "\n Por favor, asegúrate de completarla a tiempo. ";
            $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idUsuario', 'Recordatorio de tarea', '$fechaCreacion', '$detallesNotificacion' )";    
            $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

            $sqlInvitados= "SELECT usuario, correo from usuarios where id = '$idUsuario'";
            $queryInvitados = $Conexion->query($sqlInvitados);
            $row = $queryInvitados->fetch_assoc();
            $usuarioInvitado = $row['usuario'];
            $correoInvitado = $row['correo'];

            //Envio del correo
            $nombreEmpresa = 'PerfectWedding';
            $destino = 'dianapdz09@gmail.com'; //correo del cliente
            $asunto = 'Recordatorio de tarea';

            $contenido = '
                <html> 
                    <body> 
                        <h2>¡Hola '.$usuarioInvitado.'! </h2>
                        <p> 
                            Este es un recordatorio de que la tarea  "' . $titulo . '" tiene como fecha límite ' . $fecha . '<br>
                            Por favor, asegúrate de completarla a tiempo. 
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

        }
        if($porcentaje == 50){//Si la tarea esta al 50% de progreso

            $sqlNovia= "SELECT usuario, correo from usuarios where id = '$idNovia'";
            $queryNovia = $Conexion->query($sqlNovia);
            $row = $queryNovia->fetch_assoc();
            $usuarioNovia = $row['usuario'];
            $correoNovia = $row['correo'];

            $sqlEncargado= "SELECT usuario from usuarios where id = '$encargado'";
            $queryEncargado= $Conexion->query($sqlEncargado);
            $row = $queryEncargado->fetch_assoc();
            $usuarioEncargado = $row['usuario'];

            //Enviar notificacion
            $detallesNotificacion = "La tarea " . $titulo . " está ahora al 50% de progreso. \n El encargado de esta tarea es " . $usuarioEncargado . "\n Por favor, asegúrate de completarla a tiempo. ";
            $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idNovia', 'Tarea al 50% de progreso', '$fechaCreacion', '$detallesNotificacion' )";    
            $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

            //Envio del correo
            $nombreEmpresa = 'PerfectWedding';
            $destino = 'dianapdz09@gmail.com'; //correo del cliente
            $asunto = 'Tarea al 50% de progreso';

            $contenido = '
                <html> 
                    <body> 
                        <h2>¡Hola '.$usuarioNovia.'! </h2>
                        <p> 
                            La tarea  "' . $titulo . '" está ahora al 50% de progreso. <br>
                            El encargado de esta tarea es ' . $usuarioEncargado . '. ¡Sigue adelante!"
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
        }
        if($fechaActual1Semana >= $fecha){
            $sqlNovia= "SELECT usuario, correo from usuarios where id = '$idNovia'";
            $queryNovia = $Conexion->query($sqlNovia);
            $row = $queryNovia->fetch_assoc();
            $usuarioNovia = $row['usuario'];
            $correoNovia = $row['correo'];

            $sqlEncargado= "SELECT usuario from usuarios where id = '$encargado'";
            $queryEncargado= $Conexion->query($sqlEncargado);
            $row = $queryEncargado->fetch_assoc();
            $usuarioEncargado = $row['usuario'];

            //Enviar notificacion
            $detallesNotificacion = "La tarea " . $titulo . " está ahora al 50% de progreso. \n El encargado de esta tarea es " . $usuarioEncargado . "\n\r Por favor, asegúrate de completarla a tiempo. ";
            $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idNovia', 'Tarea cerca de la fecha límite', '$fechaCreacion', '$detallesNotificacion' )";    
            $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

            //Envio del correo
            $nombreEmpresa = 'PerfectWedding';
            $destino = 'dianapdz09@gmail.com'; //correo del cliente
            $asunto = 'Tarea cerca de la fecha límite';

            $contenido = '
                <html> 
                    <body> 
                        <h2>¡Hola '.$usuarioNovia.'! </h2>
                        <p> 
                            La tarea  "' . $titulo . '" está cerca de su fecha límite. <br>
                            La fecha límite para completar esta tarea es el ' . $fecha . '. <br>
                            El encargado de esta tarea es ' . $usuarioEncargado . '. ¡Hazlo lo mejor que puedas!"
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

        }

        
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
    <link rel="stylesheet" type="text/css" href="styleKanban.css">
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
<br><h3>Tabla kanban</h3>
<div class="agregarInvitadoOcultar">
    <div style="text-align: right; margin-top: 20px; padding-right: 10px; ">
        <a class="btn btn-morado" type="submit" href="agregarTareas.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Agregar tarea</a>
    </div>
</div>

<div class="kanban-container">
        <div class="kanban-column" id="pendiente">
            <h2>Pendiente</h2>
            <div class="high-priority"></div>
            <hr>
            <div class="low-priority"></div>
        </div>
        <div class="kanban-column" id="en-curso">
            <h2>En Curso</h2>
            <div class="high-priority"></div>
            <hr>
            <div class="low-priority"></div>
        </div>
        <div class="kanban-column" id="verificacion">
            <h2>Verificación por Novios</h2>
            <div class="high-priority"></div>
            <hr>
            <div class="low-priority"></div>
        </div>
        <div class="kanban-column" id="contacto-proveedores">
            <h2>Contacto con Proveedores</h2>
            <div class="high-priority"></div>
            <hr>
            <div class="low-priority"></div>
        </div>
        <div class="kanban-column" id="completado">
            <h2>Completado</h2>
            <div class="high-priority"></div>
            <hr>
            <div class="low-priority"></div>
        </div>
    </div>

    <!-- Modal para detalles de la tarea -->
    <div id="taskModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modalTitle"></h2>
        <b>Descripcion de la tarea:</b><p id="modalDescription"></p>

        <!-- Barra de progreso -->
        <label for="taskProgress">Progreso:</label>
        <input type="range" id="taskProgress" min="0" max="100" step="25" value="0">
        <span id="progressValue">0%</span>

        <!-- Validacion por los novios -->
        <div id="checkboxesvalidaciones">
        <label>Aprobar:
        <input type="checkbox" name="validacion" id="validacion" class="Ocultar">
        </label>

        <label>Marcar como tarea completada:
        <input type="checkbox" name="checkboxcompletado" id="checkboxcompletado" class="Ocultar">
        </label>
        </div>
        <label id="numeroDeSubtareas"><b>Numero Subtareas: </b><p id="numeroSubtareas"></p></label>

        <b>Fecha limite:</b><p id="fecha" name="fecha"></p>
        <b>Nombre del endargado:</b><p id="nombreEncargado" name="nombreEncargado"></p>
        <!-- Mostrar comentarios -->
        <div id="commentsSection">
            <h5>Comentarios:</h5>
            <div id="commentsList"></div>
            <textarea id="newComment" placeholder="Escribe un nuevo comentario"></textarea>
        </div>

        <!-- Botón para guardar cambios -->
        <button id="saveTask" class="btn btn-rosa">Guardar cambios</button>
    </div>
    </div>



</body>
</html>

<script>
    //Ocultar para ayudantes 
    document.addEventListener("DOMContentLoaded", function() {
        const mostrarDiv = <?php echo json_encode($mostrarDiv); ?>; 
        const elements = document.querySelectorAll('.agregarInvitadoOcultar');

        elements.forEach(function(element) {
            element.classList.toggle('hidden', !mostrarDiv); 
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Fetch de las tareas desde el backend
        fetch('kanbanTareas.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(tareas => {
                try{
                    console.log(tareas);
                    idBoda = <?php echo $idBoda ?>;
                tareas.forEach(tarea => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <div class="card-title">${tarea.titulo}</div>
                        <div class="card-percentage">Progreso: ${tarea.porcentaje}%</div>
                    `;
                    card.addEventListener('click', () => openModal(tarea, idBoda));
                    
                    // Asignar la card a la columna correcta
                    const column = document.getElementById((tarea.estatus).toLowerCase());
                    const prioritySection = tarea.prioridad == 1 ? column.querySelector('.high-priority') : column.querySelector('.low-priority');
                    prioritySection.appendChild(card);
                });
                }
                catch (error) {
                    console.error('Error al parsear JSON:', error);
                }
            
            });
    });


// Función para determinar en qué columna colocar la tarea
function getColumnId(estatus) {
    switch (estatus) {
        case 0: return 'pendiente';
        case 1: return 'en-curso';
        case 2: return 'verificacion';
        case 3: return 'contacto-proveedores';
        case 4: return 'completado';
    }
}

function openModal(tarea, idBoda) {
    const modal = document.getElementById('taskModal');
    
    // Mostrar título y descripción
    document.getElementById('modalTitle').innerText = tarea.titulo;
    document.getElementById('modalDescription').innerText = tarea.descripcion; 
    document.getElementById('numeroSubtareas').innerText = tarea.numeroSubtareas;
    document.getElementById('validacion').checked = tarea.aprobado;
    document.getElementById('checkboxcompletado').checked = tarea.completado;
    document.getElementById('fecha').innerText = tarea.fecha;
    document.getElementById('nombreEncargado').innerText = tarea.nombreEncargado;

    if(tarea.idTarea != 0){ // si soy subtarea
        document.getElementById('checkboxesvalidaciones').style.display = "none";
    }else{
        document.getElementById('checkboxesvalidaciones').style.display = "block";
    }
    if(tarea.numeroSubtareas == 0){ // si soy tarea sin subtareas
        document.getElementById('numeroDeSubtareas').style.display = "none";
    }else{
        document.getElementById('numeroDeSubtareas').style.display = "block";
    }

    // Configurar el porcentaje de progreso
    const taskProgress = document.getElementById('taskProgress');
    const progressValue = document.getElementById('progressValue');
    taskProgress.value = tarea.porcentaje;
    progressValue.innerText = `${tarea.porcentaje}%`;
    window.tareaActual = tarea;
    window.idBoda = idBoda;
    taskProgress.addEventListener('input', function () {
        progressValue.innerText = `${taskProgress.value}%`;
        actualizarEstatus(taskProgress.value, tarea.aprobado, tarea.completado);
    });

    // Cargar y mostrar los comentarios
    fetch(`kanbanComentarios.php?idTarea=${tarea.id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(comentarios => {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '';
            comentarios.forEach(comentario => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment';
                commentDiv.innerHTML = `
                    <span>${comentario.usuario}</span>
                    <p>${comentario.comentario}</p>
                    <small>${new Date(comentario.fecha).toLocaleString()}</small>
                    <hr>
                `;
                commentsList.appendChild(commentDiv);
            });
        });

    // Abrir el modal
    modal.style.display = 'flex';
    deshabilitarCheck();
}

// Función para actualizar el estatus basado en el porcentaje
function actualizarEstatus(porcentaje, aprobado, completado) {
    let estatus;
    if (porcentaje == 0) {
        estatus = 'Pendiente';
    } else if (porcentaje > 0 && porcentaje < 100) {
        estatus = 'En Curso';
    } else if (porcentaje == 100 && !aprobado) {
        estatus = 'Verificación por Novios';
    } else if (porcentaje == 100 && aprobado && !completado) {
        estatus = 'Contacto con Proveedores';
    } else if (porcentaje == 100 && aprobado && completado){
        estatus = 'Completado';
    }
    console.log('Estatus actualizado a:', estatus);
}

// Guardar cambios
document.getElementById('saveTask').addEventListener('click', () => {
    const nuevoComentario = document.getElementById('newComment').value;
    const nuevoProgreso = document.getElementById('taskProgress').value;
    const checkbox = document.getElementById('validacion').checked;
    const checkboxcompletado = document.getElementById('checkboxcompletado').checked;

    const data = {
        comentario: nuevoComentario,
        porcentaje: nuevoProgreso,
        idTarea: window.tareaActual.id,  
        idUsuario: <?php echo $idUsuario; ?>,
        idTareaPadre:  window.tareaActual.idTarea,
        idBoda: window.idBoda, 
        validar: checkbox,
        completado: checkboxcompletado
    };
    // Hacer POST para guardar el progreso y el comentario
    fetch('kanbanGuardarCambios.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
})
.then(response => {
    if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
    }
    return response.text();  // Cambiar a text() para inspeccionar la respuesta
})
.then(text => {
    console.log('Respuesta del servidor:', text);  // Ver la respuesta antes de intentar parsear como JSON
    try {
        const result = JSON.parse(text);  // Intentar convertirlo a JSON manualmente
        if (result.success) {
            alert('Cambios guardados correctamente');
            document.getElementById('taskModal').style.display = 'none'; // Cerrar modal
            location.reload(); // Recargar la página para reflejar los cambios
        } else {
            alert('Error al guardar los cambios');
        }
    } catch (error) {
        console.error('Error al parsear JSON:', error);
    }
});

    
});

// Cerrar el modal
document.querySelector('.close').addEventListener('click', () => {
    document.getElementById('taskModal').style.display = 'none';
});

    const rangeInput = document.getElementById('taskProgress');
    function deshabilitarCheck(){
        const checkboxaprobado = document.getElementById('validacion');
        const checkboxcompletado = document.getElementById('checkboxcompletado');
        const value = rangeInput.value;
        const numeroSubtareas = document.getElementById('numeroSubtareas').textContent;
        console.log(numeroSubtareas);
        checkboxaprobado.disabled = checkboxaprobado.checked || value !== "100";
        checkboxcompletado.disabled = (!((value == '100') &&  checkboxaprobado.checked) || checkboxcompletado.checked);
        rangeInput.disabled = (value == '100') || numeroSubtareas > 0;


    }

//Ocultar para ayudantes 
document.addEventListener("DOMContentLoaded", function() {
        const mostrarDiv = <?php echo json_encode($mostrarDiv); ?>; // Convierte a JSON
        const elements = document.querySelectorAll('.Ocultar');

        elements.forEach(function(element) {
            element.classList.toggle('hidden', !mostrarDiv); 
        });
    });

    rangeInput.addEventListener('input', deshabilitarCheck);

</script>