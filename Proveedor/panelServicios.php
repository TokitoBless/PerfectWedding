<?php
include_once('../Conexion/conexion.php');

// Obtiene el ID del header desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
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
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $id; ?>">Servicios</a>
                <a class="nav-item nav-link" href="conversaciones.php?id=<?php echo $id; ?>">Conversaciones</a>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $id; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<div class="header-container">
    <h3>Servicios agregados</h3>
    <a class="btn btn-info btn-agregar" type="submit" href="agregarServicio.php?id=<?php echo $id; ?>">Agregar</a>
</div>

<?php
$sqlServicios = "SELECT nombreServicio, imagen1 FROM servicios WHERE proveedor = $id";
$queryServicios = $Conexion->query($sqlServicios);

if ($queryServicios->num_rows > 0) {
    echo "<div class='card-container'>";
    while ($row = $queryServicios->fetch_assoc()) {
        $nombreServicio = $row['nombreServicio'];
        $imagen1 = 'data:image/jpeg;base64,' . base64_encode($row['imagen1']);
        echo "
        <div class='card' style='width: 12rem;' data-bs-toggle='modal' data-bs-target='#serviceModal' 
            data-img='$imagen1' data-name='$nombreServicio'>
            <div class='card-img-container'>
                <img src='$imagen1' class='card-img-top' alt='$nombreServicio'>
            </div>
            <div class='card-body'>
                <h5 class='card-title'>$nombreServicio</h5>
            </div>
        </div>";
    }
    echo "</div>";
} else {
    echo "Todavia no tienes servicios dados de alta.";
}
?>
<br><br>

<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Información del Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid" alt="Imagen del Servicio">
                <p id="modalName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('serviceModal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imgSrc = button.getAttribute('data-img');
            var name = button.getAttribute('data-name');

            var modalImage = modal.querySelector('#modalImage');
            var modalName = modal.querySelector('#modalName');

            modalImage.src = imgSrc;
            modalName.textContent = name;
        });
    });
</script>

</body>
</html>
