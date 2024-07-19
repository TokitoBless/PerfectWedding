<?php
session_start();
include_once('../Conexion/conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM proveedores WHERE id = '$id'";
    $result = $Conexion->query($sql);
    $proveedor = $result->fetch_assoc();
} else {
    // Redirigir si no hay ID de proveedor
    header('Location: infoCuenta.php?error="No se proporcionó ID de proveedor"');
    exit();
}

// Proceso para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validar($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $ApellidoPaterno = validar($_POST['apellidoPaterno']);
    $ApellidoMaterno = validar($_POST['apellidoMaterno']);
    $Nombre = validar($_POST['nombre']);
    $NombreEmpresa = validar($_POST['nombreEmpresa']);
    $Ciudad = validar($_POST['ciudad']);
    $Estado = validar($_POST['estado']);
    $Telefono = $_POST['telefono']; 
    $SitioWeb = isset($_POST['sitioWeb']) ? validar($_POST['sitioWeb']) : null;
    $Calificacion = $_POST['calificacion'];

    $sqlActualizar = "UPDATE proveedores SET apellidoPaterno = '$ApellidoPaterno', apellidoMaterno = '$ApellidoMaterno', nombre = '$Nombre', nombreEmpresa = '$NombreEmpresa', ciudad = '$Ciudad', estado = '$Estado', telefono = '$Telefono', sitioWeb = '$SitioWeb', calificacion = '$Calificacion' WHERE id = '$id'";
    $result = $Conexion->query($sqlActualizar);

    if ($result) {
        header('Location: infoPerfil.php?success=Proveedor actualizado&id=' . $id);
        exit();
    } else {
        header('Location: infoPerfil.php?id=' . $id . '&error="Error al actualizar el proveedor"');
        exit();
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
    <title>Perfil</title>
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
<h3>Informacion del Proveedor</h3>
<h6>Nombre del representante</h6>

<div class="container overflow-hidden">
  <div class="row gx-5">
    <div class="col col-padding-10">
     <div class="p-3">
        <form class="form-container-perfil" action="infoPerfil.php?id=<?php echo $id; ?>" method="POST">
            <div class="row">
                <div class="column">
                    <label for="apellido_paterno">Apellido Paterno:</label>
                    <input type="text" name="apellidoPaterno" pattern="[A-Za-z]+" title="Solo se permiten letras" value="<?php echo htmlspecialchars($proveedor['apellidoPaterno']); ?>" required>
                </div>
                <div class="column">
                    <label for="apellido_materno">Apellido Materno:</label>
                    <input type="text" name="apellidoMaterno" pattern="[A-Za-z]+" title="Solo se permiten letras" value="<?php echo htmlspecialchars($proveedor['apellidoMaterno']); ?>" required>
                </div>
                <div class="column">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" pattern="[A-Za-z]+" title="Solo se permiten letras" value="<?php echo htmlspecialchars($proveedor['nombre']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="column-full">
                    <label>Nombre Empresa:</label>
                    <input type="text" name="nombreEmpresa" value="<?php echo htmlspecialchars($proveedor['nombreEmpresa']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label>Ciudad:</label>
                    <input type="text" name="ciudad" pattern="[A-Za-z]+" title="Solo se permiten letras" value="<?php echo htmlspecialchars($proveedor['ciudad']); ?>" required>
                </div>
                <div class="column">
                    <label>Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="">Selecciona un estado</option>
                        <option value="Aguascalientes" <?php if ($proveedor['estado'] == 'Aguascalientes') echo 'selected'; ?>>Aguascalientes</option>
                        <!-- Agregar las demás opciones de estado aquí -->
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label>Teléfono:</label>
                    <input type="tel" name="telefono" pattern="[356][0-9]{9}" title="El número de celular debe comenzar con 3, 5 o 6 y tener 10 dígitos en total" value="<?php echo htmlspecialchars($proveedor['telefono']); ?>" required>
                </div>
                <div class="column">
                    <label >Sitio Web:</label>
                    <input type="url" name="sitioWeb" value="<?php echo htmlspecialchars($proveedor['sitioWeb']); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Guardar</button>
        </form>
    </div>
  </div>
    <div class="col">
      <div class="p-3">
            <center>
            <label>Calificación:</label>
            <div class="star-container">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <img src="<?php echo $i <= $proveedor['calificacion'] ? '../Imagenes/estrella_completa.png' : '../Imagenes/estrella_vacia.png'; ?>" alt="Estrella">
                <?php endfor; ?>
            </div>
            </center>
      </div>
    </div>
  </div>
</div>

</body>
</html>

