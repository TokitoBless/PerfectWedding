<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['id'])) {
    $idEncriptado = $_GET['id'];
    $id = base64_decode($idEncriptado);

    $sqlServicio = "SELECT * FROM servicios WHERE id = '$id'";
    $result = $Conexion->query($sqlServicio);
    $servicio = $result->fetch_assoc();
    $idProveedorSinEncriptar = $servicio['proveedor'];
    $idProveedor = base64_encode($idProveedorSinEncriptar);
} else {
    // Redirigir si no hay ID del servicio
    header('Location: agregarServicio.php?error="No se proporcionó ID de servicio"');
    exit();
}

// Proceso para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $descripcion = validar($_POST['descripcion']);
    $precioServicio = validar($_POST['precioServicio']);
    $palabraClave = validar($_POST['palabraClave']);
    $imagenEliminar = isset($_POST['imagenEliminar']) ? $_POST['imagenEliminar'] : null;
    
    // Actualizar datos del servicio
    $sqlActualizar = "UPDATE servicios SET descripcion = '$descripcion', precio = '$precioServicio', palabraClave = '$palabraClave' WHERE id = '$id'";
    $Conexion->query($sqlActualizar);

    // Manejo de imágenes

    for ($i = 1; $i <= 5; $i++) {
        $numeroImagen = "imagen" . $i;
        // Cargar nueva imagen
        if (isset($_FILES[$numeroImagen]['tmp_name']) && $_FILES[$numeroImagen]['error'] == UPLOAD_ERR_OK) {
            $imagen = file_get_contents($_FILES[$numeroImagen]['tmp_name']);
            $imagenLista = $Conexion->real_escape_string($imagen);
            
            $sqlActualizarImagen = "UPDATE servicios SET $numeroImagen = '$imagenLista' WHERE id = '$id'";
            $Conexion->query($sqlActualizarImagen);
        } elseif ($imagenEliminar == $i) {
            // Eliminar imagen especificada
            $sqlEliminarImagen = "UPDATE servicios SET $numeroImagen = NULL WHERE id = '$id'";
            $Conexion->query($sqlEliminarImagen);
        }
    }

    header('Location: panelServicios.php?id=' . $idProveedor . '&success=Servicio actualizado');
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
    <link rel="stylesheet" type="text/css" href="styleServicios.css">
    <title>Editar Servicios</title>
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
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $idProveedor; ?>">Servicios</a>
                <a class="nav-item nav-link" href="../Chats/listaMensajes.php?idUsuario=<?php echo $id; ?>&ind=P">Conversaciones</a>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $idProveedor; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br>
<h3>Editar Servicio</h3>

<form action="editarServicio.php?id=<?php echo $idEncriptado;?>" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="row align-items-start">
            <div class="col">
                <label>Nombre del Servicio</label><br>
                <input type="text" name="nombreServicio" pattern="[A-Za-z\s]+" title="Solo se permiten letras" value="<?php echo htmlspecialchars($servicio['nombreServicio']); ?>" disabled readonly>
                <br><br>
                <label>Descripción</label><br>
                <textarea name="descripcion" style="height: 100px" required><?php echo htmlspecialchars($servicio['descripcion']); ?></textarea>
                <br><br>
                <label>Precio del Servicio</label><br>
                $<input type="text" name="precioServicio" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número con al menos 2 dígitos" value="<?php echo htmlspecialchars($servicio['precio']); ?>" required>
                <br><br>
                <label>Categoría</label><br>
                <input type="text" id="categoriaSelect" value="<?php echo htmlspecialchars($servicio['categoria']); ?>" disabled readonly>
                <br><br>
                <label>Palabras clave</label>
                <div id="listaPalabras"></div>

                <script>
                    // Variable de la palabra clave seleccionada desde la base de datos
                    var palabraClaveSeleccionada = "<?php echo htmlspecialchars($servicio['palabraClave']); ?>";

                    // Función para mostrar palabras clave
                    function cargarPalabrasClaves() {
                        const categoriaSeleccionada = document.getElementById('categoriaSelect').value;

                        if (categoriaSeleccionada !== "") { 
                            // Petición AJAX para obtener las palabras clave de la categoría seleccionada
                            const xhr = new XMLHttpRequest();
                            xhr.open("POST", "palabrasClave.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) { 
                                    // Convertir la respuesta JSON en un objeto JavaScript
                                    const palabrasClaves = JSON.parse(xhr.responseText);
                                    // Creación del select con las palabras clave
                                    let htmlSelect = "<select name='palabraClave' id='palabraClaveSelect' required>";
                                    for (let i = 0; i < palabrasClaves.length; i++) {
                                        let seleccionada = (palabrasClaves[i] === palabraClaveSeleccionada) ? "selected" : "";
                                        htmlSelect += "<option value='" + palabrasClaves[i] + "' " + seleccionada + ">" + palabrasClaves[i] + "</option>";
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

                    // Llamar a la función cuando se carga la página
                    document.addEventListener("DOMContentLoaded", cargarPalabrasClaves);
                </script>
            </div>

            <div class="col">
                <label for="img">Imágenes del Servicio (Máximo 5)</label>
                <div id="contenedor-imagenes">
                <?php
                // Mostrar imágenes ya guardadas
                for ($i = 1; $i <= 5; $i++) {
                    $numeroImagen = "imagen" . $i;
                    if (!empty($servicio[$numeroImagen])) {
                        // Mostrar imagen
                        $imagen = 'data:image/jpeg;base64,' . base64_encode($servicio[$numeroImagen]);
                        echo "<div class='subida-imagen' id='imagen-div-$i'>";
                        echo "<div id='imagen-$i'>";
                        echo "<img src='$imagen' class='card-img-top' style='width: 100px; height: 100px;' alt='Imagen $i'>";
                        echo "</div>";
                        // Botón para subir una nueva imagen
                        echo "<input type='file' name='imagen$i' accept='image/*'>";
                        echo "<a href='#' onclick='eliminarImagen(event, $i)'><img src='../Imagenes/eliminar.png' alt='Eliminar' width='20' height='20'></a>";
                        echo "</div><br>";
                    } else {
                        // Mostrar campo de subida de imagen si no hay imagen guardada
                        echo "<div class='subida-imagen' id='imagen-div-$i'>";
                        echo "<input type='file' name='imagen$i' accept='image/*'>";
                        echo "<a href='#' onclick='eliminarImagen(event, $i)'><img src='../Imagenes/eliminar.png' alt='Eliminar' width='20' height='20'></a>";
                        echo "</div><br>";
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="imagenEliminar" id="imagenEliminar">

    <center>
        <br>
        <button class="btn-submit" type="submit">Guardar</button>
    </center>
</form>

</body>
</html>

<script>
function eliminarImagen(event, imagenIndex) {
    event.preventDefault(); // Evita que el enlace siga su comportamiento por defecto
    // Oculta el contenedor de la imagen
    document.getElementById('imagen-' + imagenIndex).style.display = 'none';
    // Actualiza el campo oculto con el índice de la imagen a eliminar
    document.getElementById('imagenEliminar').value = imagenIndex;

}
</script>


