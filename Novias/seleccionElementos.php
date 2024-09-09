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
<br>

<div class="containerSE">
        <!-- Sección superior -->
        <div class="top-section" id="top-section">
            <h3>Seleccionados</h3>
            <div style="display: flex; align-items: center;">

                <div style=" padding-left: 380px;">
                    <label>Presupuesto</label>
                </div>
                
                <div style="width: 450px;"></div>
                    <label>Prioridad</label>
                </div>
            </div>

        </div>

        <!-- Sección inferior con la lista de checkboxes -->
        <div class="bottom-section">
        <h3>Opciones</h3>
        <br>
            <div class="containerSE">
                <div class="row align-items-start">
                        
                    <div class="col">
                        <!-- Novia -->
                        <div style=" padding-left: 100px;">
                            <h5>Novia</h5>
                        </div>
                        
                        <div class="checkbox-list novia">
                            <div><input type="checkbox" id="item1" value="Vestido novia"><label for="item1">Vestido</label></div>
                            <div><input type="checkbox" id="item2" value="Zapatos novia"><label for="item2">Zapatos</label></div>
                            <div><input type="checkbox" id="item3" value="Velo"><label for="item3">Velo</label></div>
                            <div><input type="checkbox" id="item4" value="Liga"><label for="item4">Liga</label></div>
                            <div><input type="checkbox" id="item5" value="Maquillaje novia"><label for="item5">Maquillaje</label></div>
                            <div><input type="checkbox" id="item6" value="Peinado novia"><label for="item6">Peinado</label></div>
                            <div><input type="checkbox" id="item7" value="Joyería"><label for="item7">Joyería</label></div>
                            <div><input type="checkbox" id="item8" value="Accesorios"><label for="item8">Accesorios</label></div>
                            <div><input type="checkbox" id="item9" value="Ramos"><label for="item9">Ramo</label></div>
                        </div>
                        <!-- Novio -->
<br>
                        <div style=" padding-left: 100px;">
                            <h5>Novio</h5>
                        </div>
                        <div class="checkbox-list novio">
                            <div><input type="checkbox" id="item10" value="Trajes"><label for="item10">Traje</label></div>
                            <div><input type="checkbox" id="item11" value="Zapatos novio"><label for="item11">Zapatos</label></div>
                            <div><input type="checkbox" id="item12" value="Corbatas"><label for="item12">Corbata</label></div>
                            <div><input type="checkbox" id="item13" value="Pañuelos"><label for="item13">Pañuelo</label></div>
                            <div><input type="checkbox" id="item14" value="Boutonniere"><label for="item14">Boutonniere</label></div>
                        </div>
                    </div>

                    <!-- Lugar -->
                    <div class="col">
                        <div style=" padding-left: 100px;">
                            <h5>Lugar</h5>
                        </div>
                        <div class="checkbox-list lugar">
                            <div><input type="checkbox" id="item15" value="Lugar"><label for="item15">Lugar</label></div>
                            <div><input type="checkbox" id="item16" value="Decoración"><label for="item16">Decoración</label></div>
                            <div><input type="checkbox" id="item21" value="Anillos"><label for="item21">Anillos</label></div>
                            <div><input type="checkbox" id="item22" value="Centros de mesa"><label for="item22">Centros de mesa</label></div>
                            <div><input type="checkbox" id="item23" value="Manteles"><label for="item23">Manteles</label></div>
                            <div><input type="checkbox" id="item26" value="Música"><label for="item26">Música</label></div>
                            <div><input type="checkbox" id="item27" value="Fotografía"><label for="item27">Fotografía</label></div>
                            <div><input type="checkbox" id="item28" value="Video"><label for="item28">Video</label></div>
                            <div><input type="checkbox" id="item32" value="Barra de banquete"><label for="item32">Barra de banquete</label></div>
                            <div><input type="checkbox" id="item33" value="Pastel"><label for="item33">Pastel</label></div>
                            <div><input type="checkbox" id="item34" value="Barra de bebidas"><label for="item34">Barra de bebidas</label></div>
                            <div><input type="checkbox" id="item35" value="Mesa de postres"><label for="item35">Mesa de postres</label></div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <!-- Damas de honor -->
                        <div style=" padding-left: 100px;">
                            <h5>Damas de honor</h5>
                        </div>
                        <div class="checkbox-list damas">
                            <div><input type="checkbox" id="item36" value="Vestidos de damas"><label for="item36">Vestidos</label></div>
                            <div><input type="checkbox" id="item37" value="Zapatos de damas"><label for="item37">Zapatos</label></div>
                            <div><input type="checkbox" id="item38" value="Ramilletes"><label for="item38">Ramilletes</label></div>
                            <div><input type="checkbox" id="item40" value="Peinado  de dama"><label for="item40">Peinado</label></div>
                            <div><input type="checkbox" id="item41" value="Maquillaje de dama"><label for="item41">Maquillaje</label></div>
                        </div>

                        <!-- Invitados -->
                        <br>
                        <div style=" padding-left: 100px;">
                            <h5>Invitados</h5>
                        </div>
                        <div class="checkbox-list invitados">
                            <div><input type="checkbox" id="item42" value="Invitaciones"><label for="item42">Invitaciones</label></div>
                            <div><input type="checkbox" id="item43" value="Recuerdos"><label for="item43">Recuerdos</label></div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const topSection = document.getElementById('top-section');

    // Guardar las referencias de las listas donde van los elementos (Novia, Novio, Lugar, etc.)
    const secciones = {
        "Novia": document.querySelector('.novia'),
        "Novio": document.querySelector('.novio'),
        "Lugar": document.querySelector('.lugar'),
        "Damas de honor": document.querySelector('.damas'),
        "Invitados": document.querySelector('.invitados')
    };

    // Función para obtener la sección según el id del checkbox
    function obtenerSeccion(checkboxId) {
        if (checkboxId >= 1 && checkboxId <= 9) {
            return "Novia";
        } else if (checkboxId >= 10 && checkboxId <= 14) {
            return "Novio";
        } else if (checkboxId >= 15 && checkboxId <= 35) {
            return "Lugar";
        } else if (checkboxId >= 36 && checkboxId <= 41) {
            return "Damas de honor";
        } else if (checkboxId >= 42 && checkboxId <= 43) {
            return "Invitados";
        }
        return null;
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                const label = checkbox.nextElementSibling.textContent;
                const seccion = obtenerSeccion(parseInt(checkbox.id.replace('item', '')));

                const newItem = document.createElement('div');
                newItem.classList.add('d-flex', 'align-items-center', 'mb-2');

                const name = document.createElement('span');
                name.textContent = label;
                name.classList.add('me-3');

                const inputPresupuesto = document.createElement('input');
                inputPresupuesto.type = 'text';
                inputPresupuesto.placeholder = '$';
                inputPresupuesto.classList.add('me-3', 'form-control');
                inputPresupuesto.pattern = '[1-9][0-9]{1,}';
                inputPresupuesto.title = 'Ingrese al menos dos dígitos validos';
                inputPresupuesto.required = true;

                const selectPrioridad = document.createElement('select');
                selectPrioridad.classList.add('me-3', 'form-select');
                selectPrioridad.required = true;
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

                botonEliminar.addEventListener('click', function() {
                    newItem.remove();
                    checkbox.checked = false;  // Deselecciona el checkbox
                    checkbox.parentElement.style.display = '';  // Muestra de nuevo el checkbox original
                });

                newItem.appendChild(name);
                newItem.appendChild(inputPresupuesto);
                newItem.appendChild(selectPrioridad);
                newItem.appendChild(botonEliminar);

                topSection.appendChild(newItem);

                checkbox.parentElement.style.display = 'none';  // Oculta el checkbox original
            }
        });
    });
});

</script>

</body>
</html>