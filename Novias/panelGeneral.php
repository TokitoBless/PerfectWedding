<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $idUsuario = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
} else {
    header('Location: panelGeneral.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

// Consulta para obtener la categoría de los elementos de boda del usuario y evento
$sqlCategoria = "
    SELECT DISTINCT e.elemento, presupuesto
    FROM elementosboda e 
    WHERE e.usuario = $idUsuario 
    AND e.evento = $idBoda
";
$queryCategoria = $Conexion->query($sqlCategoria);

if ($queryCategoria->num_rows > 0) {
    // Obtenemos las categorías de los elementos del evento
    $categorias = [];
    while ($row = $queryCategoria->fetch_assoc()) {
        $filtroCategorias[] = $row['elemento'];
        $categorias[] = "'" . $row['elemento'] . "'";
    }
    
    // Convertimos las categorías a una lista para la consulta SQL
    $listaCategorias = implode(",", $categorias);
    $filtroListaCategorias = implode(",", $filtroCategorias);
    $checkFiltrar = isset($_POST['checkFiltrar']) ? $_POST['checkFiltrar'] : '0';
    if ($checkFiltrar == '1'){
        $precioMax = isset($_POST['precioMax']) ? (int)$_POST['precioMax'] : 0;
        $calificacion = isset($_POST['calificacion']) ? (int)$_POST['calificacion'] : 0;
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
        $palabraClave = isset($_POST['palabraClave']) ? $_POST['palabraClave'] : '';

        // Armar consulta SQL con los filtros
        $sqlServicios = "SELECT * FROM servicios WHERE categoria IN ($listaCategorias)";

        if ($categoria != '') {
            $sqlServicios .= " AND categoria = '$categoria'";
        }

        if ($palabraClave != '') {
            $sqlServicios .= " AND palabraClave LIKE '%$palabraClave%'";
        }

        if ($precioMax > 0) {
            $sqlServicios .= " AND precio <= $precioMax";
        }

        if ($calificacion > 0) {
            $sqlServicios .= " AND calificacion >= $calificacion";
        }
    }
    else{
        // Consulta para obtener todos los servicios que coincidan con las categorías de los elementos
        $sqlServicios = "(
            SELECT se.* 
            FROM servicios se
            JOIN elementosboda e ON e.elemento = se.categoria
            WHERE se.precio <= e.presupuesto
            AND se.precio >= (0.7 * e.presupuesto)
            AND se.palabraClave IN (
                SELECT palabra 
                FROM palabrasclaves 
                WHERE id IN (
                    SELECT idPalabraClave
                    FROM algoritmos
                    WHERE idUsuario = '$idUsuario'
                    GROUP BY idPalabraClave
                    HAVING SUM(sugerido) >= 5
                )
            )
            LIMIT 2
        )
        UNION ALL
        (
            SELECT * 
            FROM servicios s
            WHERE s.categoria IN ($listaCategorias)
        )";
    }
    $queryServicios = $Conexion->query($sqlServicios);
    $sqlUpdate = "UPDATE algoritmos SET sugerido = '0' WHERE idEvento = $idBoda AND idUsuario = $idUsuario";
    $queryUpdate = $Conexion->query($sqlUpdate);
} else {
    echo "No se encontraron categorías asociadas a los elementos del evento.";
    exit();
}

// Consulta para obtener el precio mínimo y máximo de los servicios
$sqlPrecios = "
    SELECT MIN(s.precio) as precioMin, MAX(s.precio) as precioMax
    FROM servicios s
    WHERE s.categoria IN ($listaCategorias)
";
$queryPrecios = $Conexion->query($sqlPrecios);
$precioMin = 0;
$precioMax = 0;

if ($queryPrecios->num_rows > 0) {
    $rowPrecios = $queryPrecios->fetch_assoc();
    $precioMin = $rowPrecios['precioMin'];
    $precioMax = $rowPrecios['precioMax'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stylesPanel.css">
    <title>Tablero general</title>
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

<div class="header-container">
    <h3>Tablero General</h3>
    <button class="btn btn-lila" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Filtro</button>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtrado de elementos</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul>
        <li>Filtrar por precio</li>
        <div class="filtro-precio-container">
            <label for="precioMin">$<span id="labelPrecioMin"><?php echo number_format($precioMin); ?></span></label>
            <label for="precioMax" class="float-end">$<span id="labelPrecioMax"><?php echo number_format($precioMax); ?></span></label>
        </div>
        <input type="range" class="form-range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax+$precioMin; ?>" step="1000" id="filtroPrecio">
        <p>Precio: $<span id="rangoPrecioActual"></span></p>

        <li>Filtrar por calificacion</li>
        <div class="calificacion-estrellas">
            <img src="../Imagenes/circulo-vacio.png" class="cero" data-calificacion="0" alt="0 estrellas" width="25" height="25">
            <img src="../Imagenes/estrella_vacia.png" class="estrella" data-calificacion="1" alt="1 estrella" width="30" height="30">
            <img src="../Imagenes/estrella_vacia.png" class="estrella" data-calificacion="2" alt="2 estrellas" width="30" height="30">
            <img src="../Imagenes/estrella_vacia.png" class="estrella" data-calificacion="3" alt="3 estrellas" width="30" height="30">
            <img src="../Imagenes/estrella_vacia.png" class="estrella" data-calificacion="4" alt="4 estrellas" width="30" height="30">
            <img src="../Imagenes/estrella_vacia.png" class="estrella" data-calificacion="5" alt="5 estrellas" width="30" height="30">
        </div>
        <p><span id="calificacionSeleccionada">0</span> estrellas</p>
        
        <li>Filtrar por categoria</li>

        <!-- Select para las categorías -->

        <select name="categoria" id="categoriaSelect" onchange="cargarPalabrasClaves()" required>
            <option value=""></option>
            <?php
            foreach ($filtroCategorias as $categoria) {
                echo "<option value='" . htmlspecialchars($categoria) . "'>" . htmlspecialchars($categoria) . "</option>";
            }
            ?>
        </select>
        <br>
        <div id="listaPalabras"></div>
            
        
    </ul>

<center>
    <button class="btn btn-lila" onclick="filtrarServicios()">Filtrar</button>
</center>
    
  </div>
</div>
<script>//Filtro de precios
document.addEventListener('DOMContentLoaded', function () {
    var filtroPrecio = document.getElementById('filtroPrecio');
    var rangoPrecioActual = document.getElementById('rangoPrecioActual');

    // Obtener el valor mínimo dinámico del rango (de PHP o de otra fuente)
    var precioMin = parseInt(filtroPrecio.min);
    var precioMax = parseInt(filtroPrecio.max);

    // Inicializar el valor actual con el primer valor
    rangoPrecioActual.textContent = precioMin;

    // Función que se ejecuta al cambiar el valor del rango
    filtroPrecio.addEventListener('input', function() {
        if(filtroPrecio.value!=precioMin)
            rangoPrecioActual.textContent = parseInt(filtroPrecio.value)-precioMin.toLocaleString();
        else
            rangoPrecioActual.textContent = parseInt(filtroPrecio.value).toLocaleString();

    });

});
</script>

<script>//Filtro de calificacion
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todas las estrellas
        var estrellas = document.querySelectorAll('.estrella');
        var calificacionSeleccionada = document.getElementById('calificacionSeleccionada');

        // Función que actualiza las estrellas según la calificación
        function actualizarEstrellas(calificacion) {
            estrellas.forEach(function(estrella, index) {
                if (index < calificacion) {
                    estrella.src = '../Imagenes/estrella_completa.png'; // Cambiar a estrella completa
                } else {
                    estrella.src = '../Imagenes/estrella_vacia.png'; // Cambiar a estrella vacía
                }
            });
            calificacionSeleccionada.textContent = calificacion; // Actualizar la calificación mostrada
        }

        // Agregar el evento de clic a cada estrella
        estrellas.forEach(function(estrella) {
            estrella.addEventListener('click', function() {
                var calificacion = this.getAttribute('data-calificacion');
                actualizarEstrellas(calificacion);
            });
        });
        var circulo = document.querySelector('.cero');
        circulo.addEventListener('click', function() {
            var calificacion = this.getAttribute('data-calificacion');
            actualizarEstrellas(calificacion);
        });
    });


    
</script>

<script>//Filtro de categoria
function cargarPalabrasClaves() {
    const categoriaSeleccionada = document.getElementById('categoriaSelect').value;

    if (categoriaSeleccionada !== "") { // Si no está vacío el select
        // Petición AJAX para obtener las palabras clave de la categoría seleccionada
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "palabrasClaves.php", true); // Lo manda al PHP
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) { // Si se envió la solicitud y es correcta
                // Convertir la respuesta JSON en un objeto JavaScript
                const palabrasClaves = JSON.parse(xhr.responseText);
                // Creación del select con las palabras clave
                let htmlSelect = "<select name='palabraClave' id='palabraClaveSelect' required>";
                htmlSelect += "<option value=''>Selecciona una descripcion</option>";
                for (let i = 0; i < palabrasClaves.length; i++) {
                    htmlSelect += "<option value='" + palabrasClaves[i] + "'>" + palabrasClaves[i] + "</option>";
                }
                htmlSelect += "</select>";
                document.getElementById('listaPalabras').innerHTML = htmlSelect;
            }
        };
        xhr.send("categoria=" + encodeURIComponent(categoriaSeleccionada));
    } else {
        document.getElementById('listaPalabras').innerHTML = "";
    }
}

</script>

<script>//Boton de filtrar

function filtrarServicios(){
    function enviarDatos() {
        const precioMax = document.getElementById('filtroPrecio').value;
    const calificacion = document.getElementById('calificacionSeleccionada').innerText;
    const categoria = document.getElementById('categoriaSelect').value;
    const palabraClave = document.getElementById('palabraClaveSelect') ? document.getElementById('palabraClaveSelect').value : '';
    
        // Crear un formulario
        var form = document.createElement("form");
        form.method = "POST";
        form.action = "panelGeneral.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>";

        // Crear los campos del formulario
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "checkFiltrar";
        input.value = "1";

        var input1 = document.createElement("input");
        input1.type = "hidden";
        input1.name = "precioMax";
        input1.value = precioMax;

        var input2 = document.createElement("input");
        input2.type = "hidden";
        input2.name = "calificacion";
        input2.value = calificacion;

        var input3 = document.createElement("input");
        input3.type = "hidden";
        input3.name = "categoria";
        input3.value = categoria;

        var input4 = document.createElement("input");
        input4.type = "hidden";
        input4.name = "palabraClave";
        input4.value = palabraClave;

        // Agregar los campos al formulario
        form.appendChild(input);
        form.appendChild(input1);
        form.appendChild(input2);
        form.appendChild(input3);
        form.appendChild(input4);

        // Agregar el formulario al body y enviarlo
        document.body.appendChild(form);
        form.submit();
    }

    // Ejemplo de uso
    enviarDatos();

}


</script>



<?php
if ($queryServicios->num_rows > 0) {
    echo "<div class='card-container'>";
    while ($row = $queryServicios->fetch_assoc()) {
        $idServicio = $row['id'];
        $idProveedor = $row['proveedor'];
        $nombreServicio = $row['nombreServicio'];
        $descripcionServicio = $row['descripcion'];
        $precio = $row['precio'];
        $calificacion = $row['calificacion'];
        $categoria = $row['categoria'];
        $palabraClave = $row['palabraClave'];

        $sqlInformacionProveedor = "SELECT * FROM proveedores WHERE id = '$idProveedor'";
        $queryInformacionProveedor= $Conexion->query($sqlInformacionProveedor);
        $rowProveedor = $queryInformacionProveedor->fetch_assoc();

        $nombreProveedor = $rowProveedor['nombre'] . " " . $rowProveedor['apellidoPaterno'] . " " . $rowProveedor['apellidoMaterno'];
        $sitioWeb = $rowProveedor['sitioWeb'];

        // Preparar imágenes
        $imagenes = [];
        for ($i = 1; $i <= 5; $i++) {
            $campoImagen = 'imagen' . $i;
            if (!empty($row[$campoImagen])) {
                $imagenes[] = 'data:image/jpeg;base64,' . base64_encode($row[$campoImagen]);
            }
        }

        echo "
        <div class='card' style='width: 12rem;' data-bs-toggle='modal' data-bs-target='#serviceModal' 
            data-id='$idServicio' data-idUsuario='$idUsuario' data-idBoda='$idBoda' data-categoria='$categoria' data-palabraClave='$palabraClave' data-cali='$calificacion' data-images='" . json_encode($imagenes) . "' data-name='$nombreServicio' data-descrip='$descripcionServicio' data-precio='$precio' data-nombreProveedor='$nombreProveedor' data-sitioWeb='$sitioWeb'>
            <div class='card-img-container'>
                <img src='{$imagenes[0]}' class='card-img-top' alt='$nombreServicio'>
            </div>
            <div class='card-body'>
                <h5 class='card-title'>$nombreServicio</h5>
            </div>
        </div>";
    }
    echo "</div>";
} else {
    echo "No se encontraron servicios disponibles para las categorías relacionadas dentro del presupuesto.";
}
?>

<br><br>

<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="icon-container">
                    <div class="like-container" id="likeContainer">
                        <!-- Aquí se insertará la imagen del like -->
                    </div>
                    <a  href="compartir.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&idServicio=<?php echo $idServicio; ?>"><img src="../Imagenes/compartir.png" alt="Compartir" width="30" height="30"></a>
                    <a  href="guardar.php?idUsuario=<?php echo $idUsuario; ?>&idBoda=<?php echo $idBoda; ?>&idServicio=<?php echo $idServicio; ?>"><img src="../Imagenes/guardar.png" alt="Guardar" width="30" height="30"></a>
                </div>
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
                        <label>Calificación:</label>
                        <div class="star-container" id="starContainer">
                            <!-- Calificacioncita -->
                        </div>
                    </center>
                </div>
                
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" id="solicitarPresupuestoBtn" href="#" role="button">Cotización</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('serviceModal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
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
            var likeContainer = document.querySelector('#likeContainer');
            

            // Limpiar el contenido anterior
            carouselImages.innerHTML = '';
            starContainer.innerHTML = '';
            likeContainer.innerHTML = '';

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
            
            if (!sitioWeb || sitioWeb.trim() === "") {
                modalSitioWeb.textContent = "No hay sitio web";
            } else {
                modalSitioWeb.textContent = sitioWeb;
            }

            solicitarPresupuestoBtn.href = `solicitarPresupuesto.php?idBoda=${idBoda}&idUsuario=${idUsuario}&idServicio=${id}`;
            
            // Enviar datos al servidor
            const data = {
                idServicio: id,
                idUsuario: idUsuario,
                idBoda: idBoda,
                categoria: categoria,
                precio: precio,
                checkFavorito: '0',
                palabraClave: palabraClave
            };

            fetch('guardarClicks.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text()) // Recibir respuesta del servidor
            .then(data => {
                if (data && data.trim() !== ''){
                    if (confirm(data + "\n¿Quieres ajustar los presupuestos?")) {
                        // Si presiona "Aceptar", redirige 
                        window.location.href = `ajustePresupuesto.php?idBoda=${idBoda}&idUsuario=${idUsuario}&categoria=${categoria}`;
                    } else {
                        // Si presiona "Cancelar", solo se cierra el alert 
                    }
                }
            })
            .catch(error => {
               console.error('Error:', error);
            });


            var likeImg = document.createElement('img');
            likeImg.id = 'likeIcon';
            likeImg.src = '../Imagenes/like_vacio.png'; 
            likeImg.alt = 'Like';
            likeImg.style.cursor = 'pointer'; // Hacer que el cursor cambie a una mano cuando pasa sobre la imagen
            likeContainer.appendChild(likeImg);

            var isLiked = false;
            // Escuchar cambios en la imagen
            document.getElementById('likeIcon').addEventListener('click', function() {
                const likeIcon = document.getElementById('likeIcon');
                const isLiked = likeIcon.getAttribute('src') === '../Imagenes/like_completo.png'; // Verificar si está seleccionado

                if (!isLiked) {
                    likeIcon.setAttribute('src', '../Imagenes/like_completo.png');
                    
                    const data = {
                        idServicio: id,
                        idBoda: idBoda,
                        idUsuario: idUsuario,
                        categoria: categoria,
                        precio: precio,
                        checkFavorito: '1',
                        palabraClave: palabraClave
                    };
                    
                    // Enviar la solicitud al servidor
                    fetch('guardarClicks.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.text()) // Recibir respuesta del servidor
                    .then(data => {
                        if (data && data.trim() !== '') {
                            if (confirm(data + "\n¿Quieres ajustar los presupuestos?")) {
                                // Si presiona "Aceptar", redirige 
                                window.location.href = `ajustePresupuesto.php?idBoda=${idBoda}&idUsuario=${idUsuario}&categoria=${categoria}`;
                            } else {
                                // Si presiona "Cancelar", solo se cierra el alert 
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });


        });
    });
   
</script>

</body>
</html>
