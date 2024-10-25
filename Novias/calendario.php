<?php
include_once('../Conexion/conexion.php');

// Obtener el mes y año actual
$mesActual = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n'); // Si se pasa un mes por URL, lo usamos; si no, usamos el mes actual
$anioActual = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y'); // Si se pasa un año por URL, lo usamos; si no, usamos el año actual

// Obtener el usuario y boda de la URL
if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: calendario.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

// Sanitizar las variables
$idUsuario = mysqli_real_escape_string($Conexion, $idUsuario);
$idBoda = mysqli_real_escape_string($Conexion, $idBoda);

// Obtener el primer día y el último día del mes actual
$primerDiaMes = "$anioActual-$mesActual-01";
$ultimoDiaMes = date("Y-m-t", strtotime($primerDiaMes)); // Último día del mes actual

// Consultar eventos y reuniones para el mes actual
$sqlEventos = "SELECT nombreEvento, descripcion, fecha, hora, duracion, invitados FROM eventos WHERE idEvento = '$idBoda' AND fecha BETWEEN '$primerDiaMes' AND '$ultimoDiaMes'";
$sqlReuniones = "SELECT nombreReunion, temas, fecha, hora, invitados, link FROM reuniones WHERE idEvento = '$idBoda' AND fecha BETWEEN '$primerDiaMes' AND '$ultimoDiaMes'";

$queryEventos = $Conexion->query($sqlEventos);
$queryReuniones = $Conexion->query($sqlReuniones);

// Almacenar eventos y reuniones en un array solo si el usuario está invitado
$calendario = [];

function obtenerNombresInvitados($invitadosArray, $Conexion) {
    // Convierte el array de IDs en una lista separada por comas para la consulta
    $invitadosIds = implode(',', array_map('intval', $invitadosArray));
    
    // Consulta para obtener los nombres de los invitados
    $sqlInvitados = "SELECT usuario FROM usuarios WHERE id IN ($invitadosIds)";
    $queryInvitados = $Conexion->query($sqlInvitados);

    $nombresInvitados = [];
    while ($rowInvitado = $queryInvitados->fetch_assoc()) {
        $nombresInvitados[] = $rowInvitado['usuario'];
    }
    
    return implode(', ', $nombresInvitados); // Retorna una cadena con los nombres separados por comas
}

while ($row = $queryEventos->fetch_assoc()) {
    $invitados = json_decode($row['invitados'], true); // Convertir invitados a array
    if (in_array($idUsuario, $invitados)) {
        $nombresInvitados = obtenerNombresInvitados($invitados, $Conexion);
        $calendario[] = [
            'tipo' => 'evento',
            'nombre' => $row['nombreEvento'],
            'descripcion' => $row['descripcion'],
            'fecha' => $row['fecha'],
            'hora' => $row['hora'],
            'duracion' => $row['duracion'],
            'invitados' => $nombresInvitados
        ];
    }
}

while ($row = $queryReuniones->fetch_assoc()) {
    $invitados = json_decode($row['invitados'], true); // Convertir invitados a array
    if (in_array($idUsuario, $invitados)) {
        $nombresInvitados = obtenerNombresInvitados($invitados, $Conexion);
        $calendario[] = [
            'tipo' => 'reunion',
            'nombre' => $row['nombreReunion'],
            'temas' => $row['temas'],
            'fecha' => $row['fecha'],
            'hora' => $row['hora'],
            'invitados' => $nombresInvitados,
            'link' => $row['link']
        ];
    }
}

$Conexion->close();

// Navegación de mes anterior y mes siguiente
$mesAnterior = $mesActual - 1;
$anioAnterior = $anioActual;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anioAnterior--;
}

$mesSiguiente = $mesActual + 1;
$anioSiguiente = $anioActual;
if ($mesSiguiente > 12) {
    $mesSiguiente = 1;
    $anioSiguiente++;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" type="text/css" href="stylesCalendario.css">
    <title>Calendario</title>
    
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

<h3>Calendario de reuniones</h3>
<div style="text-align: right; margin-top: 20px; padding-right: 10px;">
    <a class="btn btn-morado" type="submit" href="agregarEvento.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Agregar evento</a>
    <a class="btn btn-morado" type="submit" href="agregarReunion.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>">Agregar videollamada</a>
</div>

<?php
// Arreglo de meses en español
$mesesEspanol = [
    1 => 'Enero', 
    2 => 'Febrero', 
    3 => 'Marzo', 
    4 => 'Abril', 
    5 => 'Mayo', 
    6 => 'Junio', 
    7 => 'Julio', 
    8 => 'Agosto', 
    9 => 'Septiembre', 
    10 => 'Octubre', 
    11 => 'Noviembre', 
    12 => 'Diciembre'
];
?>

<div class="calendario-nav">
    <a href="?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&mes=<?php echo $mesAnterior; ?>&anio=<?php echo $anioAnterior; ?>">
        <img src="../Imagenes/antes.png" alt="MesAnterior" width="30" height="30">
    </a>
    <span><?php echo $mesesEspanol[$mesActual] . " " . $anioActual; ?></span>
    <a href="?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&mes=<?php echo $mesSiguiente; ?>&anio=<?php echo $anioSiguiente; ?>">
        <img src="../Imagenes/siguiente.png" alt="MesSiguiente" width="30" height="30">
    </a>
</div>


<table>
    <thead>
        <tr>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sábado</th>
            <th>Domingo</th>
        </tr>
    </thead>
    <tbody id="cuerpo-calendario">
        <!-- Aquí se rellenarán las celdas del calendario -->
    </tbody>
</table>
<br>
<!-- Modal -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNombre"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <b id="modalDescripcionLabel">Descripción:</b> <p id="modalDescripcion"></p>
                <b>Fecha:</b> <p id="modalFecha"></p>
                <b>Hora:</b> <p id="modalHora"></p>
                <b id="modalDuracionLabel">Duración:</b> <p id="modalDuracion"></p>
                <b>Invitados:</b> <p id="modalInvitados"></p>
                <b id="modalLinkLabel">Link:</b> <p id="modalLink"></p>
                <b id="modalTemasLabel">Temas:</b> <p id="modalTemas"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    const calendarioData = <?php echo json_encode($calendario); ?>;

    document.addEventListener("DOMContentLoaded", function () {
        const calendario = document.getElementById("cuerpo-calendario");

        // Corregir desfase de fechas
        function corregirDesfaseFecha(fechaString) {
            const fecha = new Date(fechaString + 'T00:00:00');
            return fecha;
        }

        const primerDiaSemana = corregirDesfaseFecha("<?php echo $primerDiaMes; ?>").getDay();
        const diasEnMes = new Date(<?php echo $anioActual; ?>, <?php echo $mesActual; ?>, 0).getDate();

        let fila = document.createElement('tr');

        for (let i = 0; i < (primerDiaSemana === 0 ? 6 : primerDiaSemana - 1); i++) {
            let celdaVacia = document.createElement('td');
            fila.appendChild(celdaVacia);
        }

        for (let i = 1; i <= diasEnMes; i++) {
            let celda = document.createElement('td');
            celda.innerHTML = `<strong>${i}</strong>`;

            calendarioData.forEach(item => {
                const fecha = corregirDesfaseFecha(item.fecha);
                if (fecha.getDate() === i) {
                    let div = document.createElement('div');
                    div.classList.add(item.tipo);
                    div.textContent = item.nombre;
                    div.setAttribute('data-bs-toggle', 'modal');
                    div.setAttribute('data-bs-target', '#detalleModal');
                    div.setAttribute('data-nombre', item.nombre);
                    div.setAttribute('data-descripcion', item.tipo === 'evento' ? item.descripcion : "");
                    div.setAttribute('data-fecha', item.fecha);
                    div.setAttribute('data-hora', item.hora);
                    div.setAttribute('data-duracion', item.tipo === 'evento' ? item.duracion : "");
                    div.setAttribute('data-invitados', item.invitados);
                    div.setAttribute('data-link', item.tipo === 'reunion' ? item.link : "");
                    div.setAttribute('data-temas', item.tipo === 'reunion' ? item.temas : "");

                    celda.appendChild(div);
                }
            });

            fila.appendChild(celda);

            if ((i + primerDiaSemana - 1) % 7 === 0) {
                calendario.appendChild(fila);
                fila = document.createElement('tr');
            }
        }

        calendario.appendChild(fila);

        var modal = document.getElementById('detalleModal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var nombre = button.getAttribute('data-nombre');
            var descripcion = button.getAttribute('data-descripcion');
            var fecha = button.getAttribute('data-fecha');
            var hora = button.getAttribute('data-hora');
            var duracion = button.getAttribute('data-duracion');
            var invitados = button.getAttribute('data-invitados');
            var link = button.getAttribute('data-link');
            var temas = button.getAttribute('data-temas');
            var tipo = button.classList.contains('evento') ? 'evento' : 'reunion'; // Determina el tipo

            var modalNombre = modal.querySelector('#modalNombre');
            var modalDescripcion = modal.querySelector('#modalDescripcion');
            var modalDescripcionLabel = modal.querySelector('#modalDescripcionLabel');
            var modalFecha = modal.querySelector('#modalFecha');
            var modalHora = modal.querySelector('#modalHora');
            var modalDuracion = modal.querySelector('#modalDuracion');
            var modalDuracionLabel = modal.querySelector('#modalDuracionLabel');
            var modalInvitados = modal.querySelector('#modalInvitados');
            var modalLink = modal.querySelector('#modalLink');
            var modalLinkLabel = modal.querySelector('#modalLinkLabel');
            var modalTemas = modal.querySelector('#modalTemas');
            var modalTemasLabel = modal.querySelector('#modalTemasLabel');

            modalNombre.textContent = nombre;
            modalDescripcion.textContent = descripcion;
            modalFecha.textContent = fecha;
            modalHora.textContent = hora;
            modalDuracion.textContent = duracion;
            modalInvitados.textContent = invitados;
            modalLink.textContent = link;
            modalTemas.textContent = temas;

            if (tipo === "evento") {
                modalDescripcionLabel.style.display = 'inline-block';
                modalDuracionLabel.style.display = 'inline-block';
                modalLinkLabel.style.display = 'none';
                modalTemasLabel.style.display = 'none';
            }
            if (tipo === "reunion") {
                modalDescripcionLabel.style.display = 'none';
                modalDuracionLabel.style.display = 'none';
                modalLinkLabel.style.display = 'inline-block';
                modalTemasLabel.style.display = 'inline-block';
            }
        });
    });
</script>



</body>
</html>