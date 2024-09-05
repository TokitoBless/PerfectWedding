<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlProveedor = "SELECT * FROM proveedores WHERE id = '$id'";
    $result = $Conexion->query($sqlProveedor);
    $proveedor = $result->fetch_assoc();
} else {
    // Redirigir si no hay ID de proveedor
    header('Location: agregarServicio.php?error="No se proporcionó ID de proveedor"');
    exit();
}

if (isset($_POST['nombreServicio']) && isset($_POST['descripcion']) && isset($_POST['precioServicio']) && isset($_POST['categoria']) && isset($_POST['palabraClave'])) {
    function validar($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $NombreServicio = validar($_POST['nombreServicio']);
    $Descripcion = validar($_POST['descripcion']);
    $PrecioServicio = validar($_POST['precioServicio']);
    $Categoria = validar($_POST['categoria']);
    $PalabraClave = validar($_POST['palabraClave']);
    $calificacion = 0;

    /* Manejo las imagenes*/

    if (!isset($_FILES['imagen1']['tmp_name']) || $_FILES['imagen1']['error'] != UPLOAD_ERR_OK) {
        echo "Por favor seleccione una imagen en el primer campo";
        exit();
    }
    
    $imagen = file_get_contents($_FILES['imagen1']['tmp_name']);
    $imagen = $Conexion->real_escape_string($imagen);

    $imagenes = [];//null como default
    for ($i = 2; $i <= 5; $i++) {
        $imagenes["imagen$i"] = null;
    }
    
    for ($i = 2; $i <= 5; $i++) {//se agregan las imagenes que si se pusieron
        $numeroImagen = "imagen$i";
        if (isset($_FILES[$numeroImagen]['tmp_name']) && $_FILES[$numeroImagen]['error'] == UPLOAD_ERR_OK) {
            $imagenes[$numeroImagen] = file_get_contents($_FILES[$numeroImagen]['tmp_name']);
            $imagenes[$numeroImagen] = $Conexion->real_escape_string($imagenes[$numeroImagen]);
        }
    }
    
    $sqlAgregar = "INSERT INTO servicios (proveedor, nombreServicio, descripcion, precio, categoria, palabraClave, calificacion, imagen1, imagen2, imagen3, imagen4, imagen5) VALUES ( '$id', '$NombreServicio', '$Descripcion', '$PrecioServicio', '$Categoria', '$PalabraClave', '$calificacion', '$imagen', '{$imagenes['imagen2']}', '{$imagenes['imagen3']}', '{$imagenes['imagen4']}', '{$imagenes['imagen5']}')";
    $queryAgregar = $Conexion->query($sqlAgregar);

    if ($queryAgregar) {
        echo '<script language="javascript">alert("Se agrego un servicio exitosamente");window.location.href = "panelServicios.php?id=' . $id . '";</script>';
        
    } else {
        echo "Error: " . $sqlAgregar . "<br>" . $Conexion->error;
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
    <link rel="stylesheet" type="text/css" href="styleServicios.css">
    <title>Agregar Servicios</title>
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
<h3>Nuevo Servicio</h3>

<form action="agregarServicio.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
<div class="container">
  <div class="row align-items-start">
    <div class="col">
        <label>Nombre del Servicio</label><br>
        <input type="text" name="nombreServicio" pattern="[A-Za-z\s]+" title="Solo se permiten letras" required>
    <br><br>
        <label>Descripcion</label><br>
        <textarea name="descripcion" style="height: 100px"  required></textarea>
    <br><br>
        <label>Precio del Servicio</label><br>
        $<input type="text" name="precioServicio" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número con al menos 2 dígitos" required>
    <br><br>
    </div>
    <div class="col">
    <label for="img">Imágenes (Máximo 5)</label>
    <div id="contenedor-imagenes">
        <div class="subida-imagen">
            <input type="file" name="imagen1" accept="image/*" required>
        </div>
    </div>
    <br>
    <button type="button" id="boton-agregar-imagen" class="btn">Añadir otra imagen</button>
    <br><br>

<script> //Imagenes
document.getElementById('boton-agregar-imagen').addEventListener('click', function() {
    const contenedor = document.getElementById('contenedor-imagenes');
    const cantidadImagenesActual = contenedor.querySelectorAll('.subida-imagen').length;

    if (cantidadImagenesActual < 5) {
        const envoltura = document.createElement('div');
        envoltura.className = 'subida-imagen';

        const nuevoCampoImagen = document.createElement('input');
        nuevoCampoImagen.type = 'file';
        nuevoCampoImagen.name = `imagen${cantidadImagenesActual + 1}`;// Incrementar el nombre
        nuevoCampoImagen.accept = 'image/*';

        if (cantidadImagenesActual > 0) {
            const botonEliminar = document.createElement('img');
            botonEliminar.src = '../Imagenes/eliminar.png'; 
            botonEliminar.alt = 'Eliminar';
            botonEliminar.style.cursor = 'pointer'; 
            botonEliminar.width = 20;
            botonEliminar.height = 20;

            botonEliminar.addEventListener('click', function() {
                envoltura.remove();
                actualizarBotonAgregar();
            });

            envoltura.appendChild(botonEliminar);
        }

        envoltura.appendChild(nuevoCampoImagen);
        contenedor.appendChild(envoltura);

        actualizarBotonAgregar();
    }
});

function actualizarBotonAgregar() {
    const contenedor = document.getElementById('contenedor-imagenes');
    const cantidadImagenesActual = contenedor.querySelectorAll('.subida-imagen').length;
    const botonAgregar = document.getElementById('boton-agregar-imagen');

    botonAgregar.disabled = cantidadImagenesActual >= 5;
}
</script>

        <br><br>

            <label>Categoria</label><br>
            <select name="categoria" id="categoriaSelect" onchange="cargarPalabrasClaves()" required>
                <option value=""></option>
                <option value="Lugar">Lugar</option>
                <option value="Vestido novia">Vestido novia</option>
                <option value="Zapatos novia">Zapatos novia</option>
                <option value="Velo">Velo</option>
                <option value="Liga">Liga</option>
                <option value="Maquillaje novia">Maquillaje novia</option>
                <option value="Peinado novia">Peinado novia</option>
                <option value="Joyería">Joyería</option>
                <option value="Accesorios">Accesorios</option>
                <option value="Ramos">Ramos</option>
                <option value="Trajes">Trajes</option>
                <option value="Corbatas">Corbatas</option>
                <option value="Zapatos novio">Zapatos novio</option>
                <option value="Pañuelos">Pañuelos</option>
                <option value="Boutonniere">Boutonniere</option>
                <option value="Decoración">Decoración</option>
                <option value="Anillos">Anillos</option>
                <option value="Centros de mesa">Centros de mesa</option>
                <option value="Manteles">Manteles</option>
                <option value="Música">Música</option>
                <option value="Fotografía">Fotografía</option>
                <option value="Video">Video</option>
                <option value="Barra de banquete">Barra de banquete</option>
                <option value="Pastel">Pastel</option>
                <option value="Barra de bebidas">Barra de bebidas</option>
                <option value="Mesa de postres">Mesa de postres</option>
                <option value="Vestidos de damas">Vestidos de damas</option>
                <option value="Zapatos de damas">Zapatos de damas</option>
                <option value="Maquillaje de dama">Maquillaje de dama</option>
                <option value="Peinado de dama">Peinado de dama</option>
                <option value="Ramilletes">Ramilletes</option>
                <option value="Invitaciones">Invitaciones</option>
                <option value="Recuerdos">Recuerdos</option>
            </select>
        <br><br>

            
            <label>Palabras clave</label>
            <div id="listaPalabras"></div>
            
        <script>//Palabras claves
            function cargarPalabrasClaves() {
                const categoriaSeleccionada = document.getElementById('categoriaSelect').value;

                if (categoriaSeleccionada !== "") { //si no esta vacio el select
                    //Petición AJAX para obtener las palabras clave de la categoría seleccionada
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "palabrasClave.php", true);//lo manda al php
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) { //si se envio la solicitud y es correcta
                            // Convertir la respuesta JSON en un objeto JavaScript
                            const palabrasClaves = JSON.parse(xhr.responseText);
                            //Creacion del select con las palabras clave
                            let htmlSelect = "<select name='palabraClave' id='palabraClaveSelect' required>";
                            htmlSelect += "<option value=''>Selecciona una palabra clave</option>";
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
    </div>
  </div>
</div>
<center>
    <br>
    <button class="btn-submit"  type="submit">Agregar</button>
</center>
<script>//Validar boton de agregar
document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.querySelector('form');
    const campos = formulario.querySelectorAll('input, textarea, select');
    const botonEnviar = document.querySelector('.btn-submit');

    function comprobarForm() {
        let formularioValido = true;

        campos.forEach(campo => {
            if (campo.type === 'file') {
                if (campo.files.length === 0) {
                    formularioValido = false;
                }
            } else if (campo.value.trim() === '') {
                formularioValido = false;
            }
        });

        const palabraClaveSelect = document.getElementById('palabraClaveSelect');
        if (palabraClaveSelect && palabraClaveSelect.value.trim() === '') {
            formularioValido = false;
        }

        botonEnviar.disabled = !formularioValido;
    }

    campos.forEach(campo => {
        campo.addEventListener('input', comprobarForm);
        campo.addEventListener('change', comprobarForm);
    });

    document.addEventListener('change', comprobarForm);

    // Llamar a la función una vez para establecer el estado inicial del botón
    comprobarForm();
});

</script>
</form>

</body>
</html>
