<?php
// Conexión a la base de datos
include_once('../Conexion/conexion.php');

// Obtener el idUsuario y idBoda (puede ser desde $_GET o $_SESSION)
$idUsuarioEncriptado = $_GET['idUsuario'];
$idUsuario = base64_decode($idUsuarioEncriptado);


// Consultar la información del usuario
$queryUsuario = "SELECT usuario, nombre, apellidoPaterno, apellidoMaterno, correo FROM usuarios WHERE id = ?";
$stmtUsuario = $Conexion->prepare($queryUsuario);
$stmtUsuario->bind_param("i", $idUsuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuario = $resultUsuario->fetch_assoc();

// Consultar la información de la boda
$queryBoda = "SELECT idEvento, fechaBoda, estado, presupuestoTotal FROM bodas WHERE usuario = ?";
$stmtBoda = $Conexion->prepare($queryBoda);
$stmtBoda->bind_param("i", $idUsuario);
$stmtBoda->execute();
$resultBoda = $stmtBoda->get_result();
$boda = $resultBoda->fetch_assoc();

$idBoda = $boda['idEvento'];
$idBodaEncriptado = base64_encode($idBoda);

if (isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    $fechaBoda = $_POST['fechaBoda'];
    $estado = $_POST['estado'];
    $presupuestoTotal = $_POST['presupuestoTotal'];

    // Actualizar la información del usuario
    $queryActualizarUsuario = "UPDATE usuarios SET correo = ? WHERE id = ?";
    $stmtUsuario = $Conexion->prepare($queryActualizarUsuario);
    $stmtUsuario->bind_param("si", $correo, $idUsuario);
    $stmtUsuario->execute();

    // Actualizar la información de la boda
    $queryActualizarBoda = "UPDATE bodas SET fechaBoda = ?, estado = ?, presupuestoTotal = ? WHERE usuario = ?";
    $stmtBoda = $Conexion->prepare($queryActualizarBoda);
    $stmtBoda->bind_param("ssdi", $fechaBoda, $estado, $presupuestoTotal, $idUsuario);
    $stmtBoda->execute();

    echo '<script language="javascript">alert("Informacion actualizada");window.location.href = "infoPerfil.php?idUsuario=' . $idUsuarioEncriptado . '";</script>';
    exit();
}




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stylesPerfil.css">
    <title>Información de Usuario y Boda</title>
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
                <a class="nav-item nav-link" href="calendario.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Calendario</a>
                <a class="nav-item nav-link" href="tablaKanban.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tabla Kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown1">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Mensajes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="notificaciones.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Notificaciones</a></li>
                            <li><a class="dropdown-item" href="../Chats/listaMensajes.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?> &ind=I">Mensajes</a></li>
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
                            <li><a class="dropdown-item" href="panelGeneral.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tablero general</a></li>
                            <li><a class="dropdown-item" href="tablerosFavoritos.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>&idBoda=<?php echo $idBodaEncriptado; ?>">Tableros favoritos</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br><br>
    <div class="container">
        <h2>Información de Usuario</h2>
        <form action="infoPerfil.php?idUsuario=<?php echo $idUsuarioEncriptado; ?>" method="POST">
            <input type="hidden" name="idUsuario" value="<?php echo $idUsuarioEncriptado; ?>">
            <input type="hidden" name="idBoda" value="<?php echo $idBodaEncriptado; ?>">

            <!-- Información de Usuario -->
            <div class="form-group">
                <label for="nombreCompleto">Nombre Completo</label>
                <input type="text" id="nombreCompleto" name="nombreCompleto" readonly 
                       value="<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno']); ?>">
            </div>
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" readonly 
                       value="<?php echo htmlspecialchars($usuario['usuario']); ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required 
                       value="<?php echo htmlspecialchars($usuario['correo']); ?>">
            </div>

            <!-- Separador -->
            <div class="separator"></div>

            <!-- Información de la Boda -->
            <h2>Información de la Boda</h2>
            <div class="form-group">
                <label for="fechaBoda">Fecha de la Boda</label>
                <input type="date" id="fechaBoda" name="fechaBoda" required 
                       value="<?php echo htmlspecialchars($boda['fechaBoda']); ?>">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                <?php
                    // Lista de estados de México
                    $estados = [
                        "Aguascalientes", "Baja California", "Baja California Sur", "Campeche", "Chiapas",
                        "Chihuahua", "Ciudad de México", "Coahuila", "Colima", "Durango", "Estado de México",
                        "Guanajuato", "Guerrero", "Hidalgo", "Jalisco", "Michoacán", "Morelos", "Nayarit",
                        "Nuevo León", "Oaxaca", "Puebla", "Querétaro", "Quintana Roo", "San Luis Potosí",
                        "Sinaloa", "Sonora", "Tabasco", "Tamaulipas", "Tlaxcala", "Veracruz", "Yucatán", "Zacatecas"
                    ];

                    // Mostrar opciones y seleccionar el estado actual
                    foreach ($estados as $estado) {
                        $selected = ($boda['estado'] == $estado) ? 'selected' : '';
                        echo "<option value=\"$estado\" $selected>$estado</option>";
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="presupuestoTotal">Presupuesto Total</label>
                <input type="number" id="presupuestoTotal" name="presupuestoTotal" min="0" required 
                       value="<?php echo htmlspecialchars($boda['presupuestoTotal']); ?>">
            </div>

            <!-- Botón Guardar Cambios -->
            <button type="submit" class="btn-save">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
