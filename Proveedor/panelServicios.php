<?php
include_once('../Conexion/conexion.php');

// Obtiene el ID del header desde la URL
$idEncriptado = isset($_GET['id']) ? $_GET['id'] : '';
$id = base64_decode($idEncriptado);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Servicios</title>
</head>
<body>

<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary">
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
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $idEncriptado; ?>">Servicios</a>
                <a class="nav-item nav-link" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idEncriptado; ?>&ind=P">Conversaciones</a>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $idEncriptado; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<div class="header-container">
    <h3>Servicios agregados</h3>
    <a class="btn btn-info btn-agregar" type="submit" href="agregarServicio.php?id=<?php echo $idEncriptado; ?>">Agregar</a>
</div>

<!-- Card -->
<?php
$sqlServicios = "SELECT * FROM servicios WHERE proveedor = $id";
$queryServicios = $Conexion->query($sqlServicios);

if ($queryServicios->num_rows > 0) {
    echo "<div class='card-container'>";
    while ($row = $queryServicios->fetch_assoc()) {
        $idServicio = $row['id'];
        $idServicioEncriptado = base64_encode($idServicio);
        $nombreServicio = $row['nombreServicio'];
        $descripcionServicio = $row['descripcion'];
        $precio =  "$" . $row['precio'];
        $calificacion = $row['calificacion'];

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
            data-id='$idServicioEncriptado' data-cali='$calificacion' data-images='" . json_encode($imagenes) . "' data-name='$nombreServicio' data-descrip='$descripcionServicio' data-precio='$precio'>
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
    echo "Todavía no tienes servicios dados de alta.";
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
                <div class="container ">
                    <div class="row justify-content-start">
                        <div class="col-4">

                            <div class="carrusel-img-container">
                                <div id="carouselExample" class="carousel slide">
                                    <div class= "carrusel-img">
                                        <div class="carousel-inner" id="carouselImages">
                                            <!-- Imagenes javas -->
                                        </div>
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
                        <div class="col-4">
                            <b><p>Descripcion</p></b>
                            <p id="modalDescrip"></p>
                            <b><p>Precio</p></b>
                            <p id="modalPrecio"></p>
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
                <a class="btn btn-secondary" id="editarBtn" href="#" role="button">Editar</a>
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
            var images = JSON.parse(button.getAttribute('data-images'));
            var name = button.getAttribute('data-name');
            var descrip = button.getAttribute('data-descrip');
            var precio = button.getAttribute('data-precio');
            var calificacion = button.getAttribute('data-cali');

            var carouselImages = modal.querySelector('#carouselImages');            
            var modalName = modal.querySelector('#modalName');
            var modalDescrip = modal.querySelector('#modalDescrip');
            var modalPrecio = modal.querySelector('#modalPrecio');
            var editarBtn = modal.querySelector('#editarBtn');
            var starContainer = modal.querySelector('#starContainer');


            // Limpiar el contenido anterior
            carouselImages.innerHTML = '';
            starContainer.innerHTML = '';

            // Crear elementos de carrusel para cada imagen
            images.forEach(function(image, index) {
                var carouselItem = document.createElement('div');
                carouselItem.classList.add('carousel-item');
                if (index === 0) {
                    carouselItem.classList.add('active');
                }
                var imgElement = document.createElement('img');
                imgElement.src = image;
                imgElement.classList.add('float-start', 'w-100', 'd-block');
                imgElement.alt = 'Imagen del Servicio';
                carouselItem.appendChild(imgElement);
                carouselImages.appendChild(carouselItem);
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

            editarBtn.href = 'editarServicio.php?id=' + id;
        });
    });
</script>

</body>
</html>
