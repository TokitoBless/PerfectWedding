<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $id = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    $sqlSelecionarBoda = "SELECT presupuestoTotal FROM bodas WHERE usuario = '$id' AND idEvento = '$idBoda'";
    $querySelecionarBoda = $Conexion->query($sqlSelecionarBoda); 
    $row = mysqli_fetch_row($querySelecionarBoda);
    $preupuestoTotalBd = $row[0];
} else {
    header('Location: seleccionElementos.php?error="No se proporcionó ID de usuario ni de boda"');
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
    <title>Nuevo evento</title>
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
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $id; ?>">Calendario</a>
                <a class="nav-item nav-link" href="conversaciones.php?id=<?php echo $id; ?>">Tabla kanban</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Tableros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Tablero general</a></li>
                            <li><a class="dropdown-item" href="#">Tableros favoritos</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $id; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>

<div style="display: flex; align-items: center;">
    <div style="padding-left: 280px;">
        <label>Presupuesto total: $</label>
    </div>
    <input style="width: 10%;" id="presupuestoTotal" type="text" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número con al menos 2 dígitos" value="<?php echo htmlspecialchars($preupuestoTotalBd); ?>">
    <div style="width: 250px;"></div>
    <label>Suma de presupuestos: $<span id="sumaPresupuestos">0</span></label>
</div>

<div class="containerSE">
    <!-- Sección superior -->
    <div class="top-section" id="top-section">
        <h3>Seleccionados</h3>
        <div style="text-align: right; margin-top: 20px;">
            <button id="guardarBtn" class="btn btn-morado" style="display: none;">Guardar</button>
        </div>

        <div style="display: flex;">
            <div style="padding-left: 380px;">
                <label>Presupuesto</label>
            </div>
            <div style="padding-left: 450px;">
            <label>Prioridad</label>
                <div class="tooltip-container">
                    <img src="../Imagenes/info.png" alt="informacion" width="22" height="22">
                    <span class="tooltip-text">1 menos importante - 5 máxima importancia</span>
                </div>
            </div>
        </div>

        <div id="seleccionados">
            <!-- Dividimos en categorías -->
            <div id="seleccionados-novia"><h5>Novia</h5></div>
            <div id="seleccionados-novio"><h5>Novio</h5></div>
            <div id="seleccionados-lugar"><h5>Lugar</h5></div>
            <div id="seleccionados-damas"><h5>Damas de Honor</h5></div>
            <div id="seleccionados-invitados"><h5>Invitados</h5></div>
        </div>
    </div>

    <!-- Sección inferior con la lista de checkboxes -->
    <div class="bottom-section">
        <h3>Opciones</h3>
        <div class="containerSE">
            <div class="row align-items-start">
                <div class="col">
                    
                    <!-- Novia -->
                    <div style=" padding-left: 100px;">
                        <h5>Novia</h5>
                    </div>
                    
                    <div class="checkbox-list novia">
                        <div><input type="checkbox" id="item1" value="Vestido novia" data-categoria="novia"><label for="item1">Vestido</label></div>
                        <div><input type="checkbox" id="item2" value="Zapatos novia" data-categoria="novia"><label for="item2">Zapatos</label></div>
                        <div><input type="checkbox" id="item3" value="Velo" data-categoria="novia"><label for="item3">Velo</label></div>
                        <div><input type="checkbox" id="item4" value="Liga" data-categoria="novia"><label for="item4">Liga</label></div>
                        <div><input type="checkbox" id="item5" value="Maquillaje novia" data-categoria="novia"><label for="item5">Maquillaje</label></div>
                        <div><input type="checkbox" id="item6" value="Peinado novia" data-categoria="novia"><label for="item6">Peinado</label></div>
                        <div><input type="checkbox" id="item7" value="Joyería" data-categoria="novia"><label for="item7">Joyería</label></div>
                        <div><input type="checkbox" id="item8" value="Accesorios" data-categoria="novia"><label for="item8">Accesorios</label></div>                            <div><input type="checkbox" id="item9" value="Ramos"><label for="item9">Ramo</label></div>
                    </div>

                    <!-- Novio --><br>
                    <div style=" padding-left: 100px;">
                        <h5>Novio</h5>
                    </div>

                    <div class="checkbox-list novio">
                        <div><input type="checkbox" id="item10" value="Traje" data-categoria="novio"><label for="item10">Traje</label></div>
                        <div><input type="checkbox" id="item11" value="Zapatos novio" data-categoria="novio"><label for="item11">Zapatos</label></div>
                        <div><input type="checkbox" id="item12" value="Corbatas" data-categoria="novio"><label for="item12">Corbata</label></div>
                        <div><input type="checkbox" id="item13" value="Pañuelos" data-categoria="novio"><label for="item13">Pañuelo</label></div>
                        <div><input type="checkbox" id="item14" value="Boutonniere" data-categoria="novio"><label for="item14">Boutonniere</label></div>
                    </div>
                </div>

                 <!-- Lugar -->
                <div class="col">
                    <div style=" padding-left: 100px;">
                        <h5>Lugar</h5>
                    </div>

                    <div class="checkbox-list lugar">
                        <div><input type="checkbox" id="item15" value="Lugar" data-categoria="lugar"><label for="item15">Lugar</label></div>
                        <div><input type="checkbox" id="item16" value="Decoración" data-categoria="lugar"><label for="item16">Decoración</label></div>
                        <div><input type="checkbox" id="item21" value="Anillos" data-categoria="lugar"><label for="item21">Anillos</label></div>
                        <div><input type="checkbox" id="item22" value="Centros de mesa" data-categoria="lugar"><label for="item22">Centros de mesa</label></div>
                        <div><input type="checkbox" id="item23" value="Manteles" data-categoria="lugar"><label for="item23">Manteles</label></div>
                        <div><input type="checkbox" id="item26" value="Música" data-categoria="lugar"><label for="item26">Música</label></div>
                        <div><input type="checkbox" id="item27" value="Fotografía" data-categoria="lugar"><label for="item27">Fotografía</label></div>
                        <div><input type="checkbox" id="item28" value="Video" data-categoria="lugar"><label for="item28">Video</label></div>
                        <div><input type="checkbox" id="item32" value="Barra de banquete" data-categoria="lugar"><label for="item32">Barra de banquete</label></div>
                        <div><input type="checkbox" id="item33" value="Pastel" data-categoria="lugar"><label for="item33">Pastel</label></div>
                        <div><input type="checkbox" id="item34" value="Barra de bebidas" data-categoria="lugar"><label for="item34">Barra de bebidas</label></div>
                        <div><input type="checkbox" id="item35" value="Mesa de postres" data-categoria="lugar"><label for="item35">Mesa de postres</label></div>
                    </div>
                </div>

                <div class="col">
                    <!-- Damas de honor -->
                    <div style=" padding-left: 100px;">
                        <h5>Damas de honor</h5>
                    </div>

                    <div class="checkbox-list damas">
                        <div><input type="checkbox" id="item36" value="Vestidos de damas" data-categoria="damas"><label for="item36">Vestidos</label></div>
                        <div><input type="checkbox" id="item37" value="Zapatos de damas" data-categoria="damas"><label for="item37">Zapatos</label></div>
                        <div><input type="checkbox" id="item38" value="Ramilletes" data-categoria="damas"><label for="item38">Ramilletes</label></div>
                        <div><input type="checkbox" id="item40" value="Peinado  de dama" data-categoria="damas"><label for="item40">Peinado</label></div>
                        <div><input type="checkbox" id="item41" value="Maquillaje de dama" data-categoria="damas"><label for="item41">Maquillaje</label></div>
                    </div>

                        <!-- Invitados -->
                    <div style=" padding-left: 100px;">
                        <h5>Invitados</h5>
                    </div>

                    <div class="checkbox-list invitados">
                        <div><input type="checkbox" id="item42" value="Invitaciones" data-categoria="invitados"><label for="item42">Invitaciones</label></div>
                        <div><input type="checkbox" id="item43" value="Recuerdos" data-categoria="invitados"><label for="item43">Recuerdos</label></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="idUsuario" value="<?php echo $id; ?>"> <!-- Ejemplo de campo oculto para idUsuario -->
<input type="hidden" id="idBoda" value="<?php echo $idBoda; ?>"> <!-- Ejemplo de campo oculto para idBoda -->

<script>
// Inicializa el DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    const presupuestoTotalInput = document.getElementById('presupuestoTotal');
    const sumaPresupuestosSpan = document.getElementById('sumaPresupuestos');
    const guardarBtn = document.getElementById('guardarBtn');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const idUsuario = document.getElementById('idUsuario').value; // Obtener idUsuario
    const idBoda = document.getElementById('idBoda').value; // Obtener idBoda
    let elementos = [];

    // Actualiza la suma de presupuestos y verifica condiciones
    function actualizarSumaPresupuestos() {
        let sumaPresupuestos = 0;
        let todosValidos = true;

        elementos.forEach(el => {
            const presupuestoElemento = parseFloat(el.presupuesto) || 0;
            sumaPresupuestos += presupuestoElemento;

            // Verifica si el presupuesto o prioridad son inválidos
            if (el.presupuesto === '' || el.selectPrioridad.value === "") {
                todosValidos = false;
            }
            
        });

        sumaPresupuestosSpan.textContent = sumaPresupuestos.toFixed(2);
        verificarCondiciones(sumaPresupuestos);

        // Mostrar botón guardar solo si todos los campos son válidos
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
            const presupuestoElemento = parseFloat(el.presupuesto) || 0;

            // Si el presupuesto del elemento excede el 50% del presupuesto total
            if (presupuestoElemento > presupuestoTotal * 0.5) {
                el.inputPresupuesto.style.color = 'red';
                // Mostrar alerta
                alert(`El elemento ${el.nombre} tiene más del 50% del presupuesto total, por favor modifícalo.`);
            } else {
                el.inputPresupuesto.style.color = 'black';
            }
        });

        // Si la suma de presupuestos es mayor al presupuesto total
        if (sumaPresupuestos > presupuestoTotal) {
            sumaPresupuestosSpan.style.color = 'red';
            alert("La suma del presupuesto de todos los elementos es mayor al presupuesto total, por favor modifica el presupuesto de algún elemento o modifica el presupuesto total.");
        } else {
            sumaPresupuestosSpan.style.color = 'black';
        }
    }

    // Agregar el elemento a la categoría correcta en la sección superior
    function agregarElemento(checkbox, label, categoria) {
        const seccionCategoria = document.getElementById(`seleccionados-${categoria}`);
        const newItem = document.createElement('div');
        newItem.classList.add('d-flex', 'align-items-center', 'mb-2');

        const name = document.createElement('span');
        name.textContent = label;
        name.classList.add('me-3');

        const inputPresupuesto = document.createElement('input');
        inputPresupuesto.type = 'text';
        inputPresupuesto.placeholder = '$';
        inputPresupuesto.classList.add('me-3', 'form-control');
        inputPresupuesto.pattern = '[1-9][0-9]*'; // Asegúrate de que el patrón sea correcto
        inputPresupuesto.required = true;

        const selectPrioridad = document.createElement('select');
        selectPrioridad.classList.add('me-3', 'form-select');

        const defaultOption = document.createElement('option');
        defaultOption.value = "";  
        defaultOption.textContent = "Seleccione";  
        defaultOption.disabled = true;  
        defaultOption.selected = true;  
        selectPrioridad.appendChild(defaultOption);

        // Opciones del 1 al 5
        for (let i = 1; i <= 5; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            selectPrioridad.appendChild(option);
        }

        const botonEliminar = document.createElement('img');
        botonEliminar.src = '../Imagenes/eliminar.png';
        botonEliminar.alt = 'Eliminar';
        botonEliminar.style.cursor = 'pointer';
        botonEliminar.width = 20;
        botonEliminar.height = 20;

        // Eliminar el elemento
        botonEliminar.addEventListener('click', function() {
            newItem.remove();
            checkbox.checked = false;
            checkbox.parentElement.style.display = '';  // Muestra el checkbox de nuevo
            elementos = elementos.filter(el => el.nombre !== label);
            actualizarSumaPresupuestos();
        });

        newItem.appendChild(name);
        newItem.appendChild(inputPresupuesto);
        newItem.appendChild(selectPrioridad);
        newItem.appendChild(botonEliminar);

        seccionCategoria.appendChild(newItem);
        checkbox.parentElement.style.display = 'none';  // Oculta el checkbox de la lista inferior

        // Agregar el elemento a la lista
        elementos.push({
            nombre: label,
            presupuesto: inputPresupuesto.value,
            inputPresupuesto: inputPresupuesto,
            selectPrioridad: selectPrioridad
        });

        inputPresupuesto.addEventListener('input', function() {
            const elemento = elementos.find(el => el.nombre === label);
            elemento.presupuesto = inputPresupuesto.value;
            actualizarSumaPresupuestos();
        });

        selectPrioridad.addEventListener('change', function() {
            actualizarSumaPresupuestos();
        });
        presupuestoTotalInput.addEventListener('change', function() {
            actualizarSumaPresupuestos();
        });

        actualizarSumaPresupuestos();
    }

    // Escuchar cambios en los checkboxes
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                const label = checkbox.nextElementSibling.textContent;
                const categoria = checkbox.getAttribute('data-categoria');
                agregarElemento(checkbox, label, categoria);
            }
        });
    });

    // Guardar los elementos seleccionados en la BD
    guardarBtn.addEventListener('click', function() {
        let elementosData = [];

        elementos.forEach(el => {
            const presupuesto = el.inputPresupuesto.value;
            const prioridad = el.selectPrioridad.value;
            elementosData.push({
                nombre: el.nombre,
                presupuesto: presupuesto,
                prioridad: prioridad
            });
        });

        const dataToSend = {
            idUsuario: idUsuario,
            idBoda: idBoda,
            elementos: elementosData
        };
        const url = `descripcionElementos.php?idUsuario=${encodeURIComponent(idUsuario)}&idBoda=${encodeURIComponent(idBoda)}`;

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

