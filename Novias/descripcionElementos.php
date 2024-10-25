<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $id = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    $sqlSelecionarBoda = "SELECT DISTINCT elemento FROM elementosboda WHERE usuario = '$id' AND evento = '$idBoda'";
    $querySelecionarBoda = $Conexion->query($sqlSelecionarBoda);
    $elementosBoda = [];
    while ($row = mysqli_fetch_assoc($querySelecionarBoda)) {
        // Reemplaza guiones bajos por espacios en los elementos obtenidos de la base de datos
        $elementosBoda[] = str_replace('_', ' ', $row['elemento']);
    }
} else {
    header('Location: descripcionElementos.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar los datos recibidos en POST
    echo "<h3>Datos Recibidos:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Bandera para verificar si hubo actualizaciones exitosas
    $actualizado = false;

    foreach ($elementosBoda as $elemento) {
        // Verificar si se ha enviado una descripción para este elemento
        echo "Procesando elemento: $elemento<br>";

        // Reemplazar espacios por guiones bajos en el nombre del campo POST
        $elementoPost = str_replace(' ', '_', $elemento);

        if (isset($_POST[$elementoPost])) {
            $descripcion = trim($_POST[$elementoPost]);

            // Verificar si la descripción no está vacía
            if (!empty($descripcion)) {
                $descripcion = $Conexion->real_escape_string($descripcion);

                // Actualiza la descripción del elemento en la base de datos
                $sqlUpdate = "UPDATE elementosboda SET descripcion = '$descripcion' 
                              WHERE usuario = '$id' AND evento = '$idBoda' AND elemento = '$elemento'";
                if ($Conexion->query($sqlUpdate)) {
                    echo "Elemento actualizado correctamente: $elemento con descripción: $descripcion<br>";
                    $actualizado = true;
                } else {
                    echo "Error al actualizar el elemento: $elemento - " . $Conexion->error . "<br>";
                }
            } else {
                echo "Descripción vacía para el elemento: $elemento<br>";
            }
        } else {
            echo "No se recibió el campo para el elemento: $elemento<br>";
        }
    }

    // Si se actualizó al menos un elemento, redirigir; de lo contrario, mostrar mensaje
    if ($actualizado) {
        header("Location: panelGeneral.php?idUsuario=$id&idBoda=$idBoda");
        exit();
    } else {
        echo "No se actualizaron elementos.";
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Nuevo evento</title>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>

<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <p class="title_nav">
      <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
      Perfect Wedding
    </p>
  </div>
</nav>

<form action="descripcionElementos.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>" method="POST">
<br><h3>Describe tu boda</h3>
<div style="text-align: right; margin-top: 20px; padding-right: 10px;">
    <button type="submit" class="btn btn-lila">Guardar</button>
    <a type="button" class="btn btn-rosa" href="textoLibre.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>">Texto libre</a>
</div>
<div class="container mt-4">
    <!-- Categorías de Novia -->
    <h4>Novia</h4>
    <?php
    $novia = [
        'Vestido novia' => ['Linea A', 'Cenicienta', 'Sirena', 'Columna', 'Cortos'],
        'Zapatos novia' => ['Punta descubierta', 'Plataforma', 'Kitten heels', 'Stiletto', 'Botines', 'Correa en el tobillo', 'Correa en forma de T', 'Sandalias', 'Alpargatas'],
        'Velo' => ['Francés', 'Blusher', 'De fuente', 'Vals', 'Capilla', 'Catedral', 'Mantilla'],
        'Liga' => ['Con brillos', 'Pedrería', 'Encaje', 'Adornos florales'],
        'Maquillaje novia' => ['Natural', 'Clasico', 'Ahumado', 'Pin up', 'Glam'],
        'Peinado novia' => ['Coleta', 'Trenza', 'Recogidos', 'Pelo suelto', 'Flequillo'],
        'Joyeria' => ['Oro', 'Plata'],
        'Accesorios' => ['Perlas', 'Flores', 'Brillos'],
        'Ramo' => ['Domo', 'Pageant bouquet', 'Flores silvestres', 'Cascada', 'Tallo largo', 'Bouquet', 'Ramilletes'],
        'Color' => ['Negro', 'azul', 'marrón', 'gris', 'verde', 'naranja', 'rosa', 'púrpura', 'rojo', 'blanco', 'amarillo']
    ];

    foreach ($novia as $label => $options) {
        if (in_array($label, $elementosBoda)) {
            echo "<div class='mb-3'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'required>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        } else {
            echo "<div class='mb-3 hidden'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select'  id='{$label}' name='{$label}'>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }
    ?>

    <!-- Categorías de Novio -->
    <h4>Novio</h4>
    <?php
    $novio = [
        'Traje' => ['Frac', 'Chaqué', 'Esmoquin', 'Terno'],
        'Zapatos novio' => ['Oxford clásicos', 'Oxford Legate', 'Derby', 'Charol', 'Monkstrap', 'Mocasines'],
        'Corbata' => ['Clásica', 'Ascot', 'Slim', 'Moño', 'Sin corbata'],
        'Pañuelo' => ['Liso', 'Estampado'],
        'Boutonniere' => ['Clásico', 'Fistol']
    ];

    foreach ($novio as $label => $options) {
        if (in_array($label, $elementosBoda)) {
            echo "<div class='mb-3'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}' required>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        } else {
            echo "<div class='mb-3 hidden'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }
    ?>

    <!-- Categorías de Lugar -->
    <h4>Lugar</h4>
    <?php
    $lugar = [
        'Lugar' => ['playa', 'casino', 'jardin', 'salón de eventos'],
        'Decoración' => ['Clásica', 'Moderna', 'Vintage', 'Glamorosa', 'Romántica', 'Rústica', 'Urbana', 'Minimalista', 'Mexicana'],
        'Anillos' => ['Media caña', 'Planos', 'Almendrados', 'Combinados', 'Estilo eternidad', 'Entrecruzados', 'Piedras preciosas'],
        'Centros de mesa' => ['Clásicos', 'Velas', 'Altos con flores', 'Macetas', 'Camino de flores', 'Candelabros', 'Rustico'],
        'Manteles' => ['Algodón', 'Lino', 'Estampados', 'Lisos', 'Bordados'],
        'Musica' => ['Clásica', 'Instrumental', 'Jazz', 'Bossa nova', 'Mariachi', 'Salsa', 'Cumbia', 'Baladas', 'Rock', 'Pop'],
        'Fotografia' => ['Tradicional', 'Documental', 'Artística', 'Natural'],
        'Video' => ['Highlight', 'Tráiler', 'Película', 'Documental'],
        'Barra de banquete' => ['Quesos', 'Mariscos', 'Antojitos mexicanos', 'Carnes frías', 'Bocadillos y canapés', 'Comida internacional', 'Vegetariana'],
        'Pastel' => ['Drip cake', 'Layer cake', 'Naked cake', 'Seminaked cake', 'Brushstroke cake', 'Ruffle cake', 'Pastel de chantilly', 'Topsy-turvy cake', 'Pastel con fondant'],
        'Barra de bebidas' => ['Café y Té', 'Limonadas', 'Coctelería', 'Aguas frescas', 'Cervezas', 'Gazpacho', 'Zumos'],
        'Mesa de postres' => ['Clásica', 'Chocolatera', 'Frutas', 'Dulces típicos', 'Panes', 'Envinados', 'Botanas']
    ];

    foreach ($lugar as $label => $options) {
        if (in_array($label, $elementosBoda)) {
            echo "<div class='mb-3'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'required>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        } else {
            echo "<div class='mb-3 hidden'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }
    ?>

    <!-- Categorías de Damas de Honor -->
    <h4>Damas de honor</h4>
    <?php
    $damasHonor = [
        'Vestidos de damas' => ['Largos y elegantes', 'Cortos', 'Estampados', 'Matelizados', 'Ligeros'],
        'Zapatos de damas' => ['Punta descubierta', 'Plataforma', 'Kitten heels', 'Stiletto', 'Botines', 'Correa en el tobillo', 'Correa en forma de T', 'Sandalias', 'Alpargatas'],
        'Maquillaje de dama' => ['Natural', 'Clasico', 'Ahumado', 'Pin up', 'Glam'],
        'Peinado de dama' => ['Coleta', 'Trenza', 'Recogidos', 'Pelo suelto', 'Flequillo'],
        'Ramilletes' => ['Domo', 'Pageant bouquet', 'Flores silvestres', 'Cascada', 'Tallo largo', 'Bouquet']
    ];

    foreach ($damasHonor as $label => $options) {
        if (in_array($label, $elementosBoda)) {
            echo "<div class='mb-3'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'required>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        } else {
            echo "<div class='mb-3 hidden'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }
    ?>

    <!-- Categorías de Invitados -->
    <h4>Invitados</h4>
    <?php
    $invitados = [
        'Invitaciones' => ['Elegantes', 'Clásicas', 'Informales', 'Personalizadas', 'Con texturas', 'Temáticas', 'Recordatorio', 'Botánico'],
        'Recuerdos' => ['Velas', 'Dulces', 'Chocolates', 'Pantuflas', 'Sandalias', 'Plantas', 'Llaveros', 'Abanicos']
    ];

    foreach ($invitados as $label => $options) {
        if (in_array($label, $elementosBoda)) {
            echo "<div class='mb-3'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'required>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        } else {
            echo "<div class='mb-3 hidden'>";
            echo "<label for='{$label}' class='form-label'>{$label}</label>";
            echo "<select class='form-select' id='{$label}' name='{$label}'>";
            echo "<option value=''>Selecciona una opción</option>";
            foreach ($options as $option) {
                echo "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }
    ?>
</div>

</form>

</body>
</html>

