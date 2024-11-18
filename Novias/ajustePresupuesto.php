<?php
include_once('../Conexion/conexion.php');


// Obtener el usuario y boda de la URL
if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idEncriptado = $_GET['idUsuario'];
    $idUsuario = base64_decode($idEncriptado);
    $idBodaEncriptado = $_GET['idBoda'];
    $idBoda = base64_decode($idBodaEncriptado);
    $categoriaUrl = $_GET['categoria'];
    $sqlSelecionarBoda = "SELECT presupuestoTotal FROM bodas WHERE idEvento = '$idBoda'";
    $querySelecionarBoda = $Conexion->query($sqlSelecionarBoda); 
    $row = mysqli_fetch_row($querySelecionarBoda);
    $presupuestoTotal = $row[0];
} else {
    header('Location: ajuatePresupuesto.php?error="No se proporcionó ID de usuario ni de boda"');
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
    <title>Ajuste de presupuesto</title>
    
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

<h3>Ajuste de presupuesto</h3>
    <br>
    <div style="display: flex; align-items: center;">
    <div style="padding-left: 280px;">
        <label>Presupuesto total: $</label>
    </div>
    <input style="width: 10%;" id="presupuestoTotal" type="text" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número con al menos 2 dígitos" value="<?php echo htmlspecialchars($presupuestoTotal); ?>">
    <div style="width: 250px;"></div>
    <label>Suma de presupuestos: $<span id="sumaPresupuestos">0</span></label>
</div>
<br><br>

<div style="text-align: right; margin-top: 30px;">
    <button id="guardarBtn" class="btn btn-morado" style="display: none;">Guardar</button>
</div>

<?php
// Consulta para obtener los elementos de la boda
$sqlElementosBoda = "SELECT * FROM elementosboda WHERE evento = '$idBoda' ORDER BY prioridad ASC";
$queryElementosBoda = $Conexion->query($sqlElementosBoda);

// Array para almacenar las filas
$elementos = [];
while ($row = $queryElementosBoda->fetch_assoc()) {
    $elementos[] = $row;
}

// Mostrar la primera tabla con la categoría
echo "<h4>Categoría vista</h4>";
echo "<table class='table table-striped'>";
echo "<thead>";
echo "<tr>";
echo "<th>Categoría</th>";
echo "<th>Presupuesto</th>";
echo "<th>Prioridad</th>";
echo "</tr>";
echo "</thead>";

echo "<tbody>";
// Buscar la categoría seleccionada y mostrarla
foreach ($elementos as $elemento) {
    if ($elemento['elemento'] === $categoriaUrl) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($elemento['elemento']) . "</td>";
        echo "<td><input type='number' value='" . htmlspecialchars($elemento['presupuesto']) . "' class='presupuestoInput' data-nombre='" . htmlspecialchars($elemento['elemento']) . "'></td>";
        echo "<td class='prioridad'>" . htmlspecialchars($elemento['prioridad']) . "</td>";
        echo "</tr>";
        break; 
    }
}
echo "</tbody>";



echo "</table><br><br>";


// Mostrar las categorías restantes
echo "<h4>Lista de categorías recomendadas de baja prioridad </h4>";
echo "<table class='table table-striped'>";
echo "<tr>";
echo "<th>Categoría</th>";
echo "<th>Presupuesto</th>";
echo "<th>Prioridad</th>";
echo "</tr>";

// Contador para mostrar un máximo de 3 filas
$contador = 0;

// Mostrar las 3 primeras categorías con menor prioridad
foreach ($elementos as $elemento) {
    if ($elemento['elemento'] !== $categoriaUrl) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($elemento['elemento']) . "</td>";
        echo "<td><input type='number' value='" . htmlspecialchars($elemento['presupuesto']) . "' class='presupuestoInput' data-nombre='" . htmlspecialchars($elemento['elemento']) . "'></td>";
        echo "<td class='prioridad'>" . htmlspecialchars($elemento['prioridad']) . "</td>";
        echo "</tr>";

        $contador++;
        if ($contador >= 3) {
            break; 
        }
    }
}

echo "</table>";
?>
<input type="hidden" id="idUsuario" value="<?php echo $idUsuario; ?>"> 
<input type="hidden" id="idBoda" value="<?php echo $idBoda; ?>">

<script>
// Inicializa el DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    const presupuestoTotalInput = document.getElementById('presupuestoTotal');
    const sumaPresupuestosSpan = document.getElementById('sumaPresupuestos');
    const guardarBtn = document.getElementById('guardarBtn');
    const presupuestoInputs = document.querySelectorAll('.presupuestoInput');
    const idUsuario = document.getElementById('idUsuario').value; 
    const idBoda = document.getElementById('idBoda').value; 

    let elementos = Array.from(presupuestoInputs).map(input => ({
        nombre: input.getAttribute('data-nombre'), 
        presupuesto: input.value,
        inputPresupuesto: input
    }));

    // Actualiza la suma de presupuestos y verifica condiciones
    function actualizarSumaPresupuestos() {
        let sumaPresupuestos = 0;
        let todosValidos = true;

        elementos.forEach(el => {
            const presupuestoElemento = parseFloat(el.inputPresupuesto.value) || 0;
            sumaPresupuestos += presupuestoElemento;

            if (el.inputPresupuesto.value === '') {
                todosValidos = false;
            }
        });

        sumaPresupuestosSpan.textContent = sumaPresupuestos.toFixed(2);
        verificarCondiciones(sumaPresupuestos);

        if (todosValidos && sumaPresupuestos > 0) {
            guardarBtn.style.display = 'inline-block';
        } else {
            guardarBtn.style.display = 'none';
        }
    }

    // Verifica las condiciones del presupuesto
    function verificarCondiciones(sumaPresupuestos) {
        const presupuestoTotal = parseFloat(presupuestoTotalInput.value) || 0;

        elementos.forEach(el => {
            const presupuestoElemento = parseFloat(el.inputPresupuesto.value) || 0;

            if (presupuestoElemento > presupuestoTotal * 0.5) {
                el.inputPresupuesto.style.color = 'red';
                alert(`El presupuesto del elemento ${el.nombre} es mayor al 50% del presupuesto total.`);
            } else {
                el.inputPresupuesto.style.color = 'black';
            }
        });

        if (sumaPresupuestos > presupuestoTotal) {
            sumaPresupuestosSpan.style.color = 'red';
            alert("La suma del presupuesto de todos los elementos es mayor al presupuesto total.");
        } else {
            sumaPresupuestosSpan.style.color = 'black';
        }
        
    }

    presupuestoInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            actualizarSumaPresupuestos();
        });
    });

    presupuestoTotalInput.addEventListener('change', function() {
        actualizarSumaPresupuestos();
    });

// Guardar los elementos seleccionados en la BD
guardarBtn.addEventListener('click', function() {
    let elementosData = [];

    const presupuestoInputs = document.querySelectorAll('.presupuestoInput');
    
    presupuestoInputs.forEach(input => {
        const presupuesto = input.value;
        const prioridad = input.closest('tr').querySelector('.prioridad').textContent; 
        const nombre = input.getAttribute('data-nombre'); 

        elementosData.push({
            nombre: nombre,
            presupuesto: presupuesto,
            prioridad: prioridad
        });
    });

    const dataToSend = {
        idUsuario: idUsuario,
        idBoda: idBoda,
        elementos: elementosData
    };

    const url = `panelGeneral.php?idUsuario=${btoa(idUsuario)}&idBoda=${btoa(idBoda)}`;

    // Enviar los datos al archivo PHP mediante fetch()
    fetch('guardarElementos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(dataToSend)
    })
    .then(response => response.text()) // Recibir respuesta del servidor
    .then(data => {
        alert(data); // Mostrar la respuesta del servidor
        window.location.href = url;
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


});
</script>


</body>
</html>
