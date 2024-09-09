<?php
include_once('../Conexion/conexion.php');

// Obtiene el ID del header desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Redirigir si no hay ID del servicio
    header('Location: crearEvento.php?error="No se proporcionó ID del usuario"');
    exit();
}
if (isset($_POST['fechaBoda']) && isset($_POST['estado']) && isset($_POST['presupuestoTotal'])){
    $fechaBoda = $_POST['fechaBoda'];
    $estado = $_POST['estado'];
    $presupuesto = $_POST['presupuestoTotal'];
    
    $sqlRevisarFecha = "SELECT fechaRegistro FROM usuarios WHERE id = '$id'";
    $queryRevisarFecha = $Conexion->query($sqlRevisarFecha);
    $row = mysqli_fetch_row($queryRevisarFecha);
    $fechaRegistro = $row[0];
    $fechaLimite = new DateTime($fechaRegistro);
    $fechaLimite = $fechaLimite->modify('+1 month');
    $fechaLimite = $fechaLimite->format('Y-m-d'); 

    if($fechaBoda > $fechaLimite){
        //la fecha ingresada el mayor de un mes
        $sqlIngresarBoda = "INSERT INTO bodas(usuario, fechaBoda, estado, presupuestoTotal) VALUE ('$id', '$fechaBoda', '$estado', '$presupuesto')";
        $queryIngresarBoda = $Conexion->query($sqlIngresarBoda);
        if($queryIngresarBoda){
            $sqlIdBoda = "SELECT MAX(idEvento) FROM bodas WHERE usuario = '$id'";
            $queryIdBoda = $Conexion->query($sqlIdBoda);
            $row = mysqli_fetch_row($queryIdBoda);
            $idBoda = $row[0];
            header('location:seleccionElementos.php?success=Evento creado&idUsuario=' . $id . '&idBoda='.$idBoda.'');
            exit();
        }
    }else{
        echo '<script language="javascript">alert("La fecha de la boda debe de ser de un mes despues de su registro.\r\nPuede ser despues de la fecha '.$fechaLimite.'");</script>';
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

<center><br><br><br><br>
<form action="crearEvento.php?id=<?php echo $id; ?>"  method="POST">
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            <label>Fecha de la boda</label>
            <br><br>
            <label>Estado</label>
            <br><br>
            <label>Presupuesto total</label>
        </div>

        <div class="col-4">
            <input type="date" name="fechaBoda" required>
            <br><br>
            <select id="estado" name="estado" required>
                <option value="">Selecciona un estado</option>
                <option value="Aguascalientes">Aguascalientes</option>
                <option value="Baja California">Baja California</option>
                <option value="Baja California Sur">Baja California Sur</option>
                <option value="Campeche">Campeche</option>
                <option value="Chiapas">Chiapas</option>
                <option value="Chihuahua">Chihuahua</option>
                <option value="Ciudad de México">Ciudad de México</option>
                <option value="Coahuila">Coahuila</option>
                <option value="Colima">Colima</option>
                <option value="Durango">Durango</option>
                <option value="Guanajuato">Guanajuato</option>
                <option value="Guerrero">Guerrero</option>
                <option value="Hidalgo">Hidalgo</option>
                <option value="Jalisco">Jalisco</option>
                <option value="México">México</option>
                <option value="Michoacán">Michoacán</option>
                <option value="Morelos">Morelos</option>
                <option value="Nayarit">Nayarit</option>
                <option value="Nuevo León">Nuevo León</option>
                <option value="Oaxaca">Oaxaca</option>
                <option value="Puebla">Puebla</option>
                <option value="Querétaro">Querétaro</option>
                <option value="Quintana Roo">Quintana Roo</option>
                <option value="San Luis Potosí">San Luis Potosí</option>
                <option value="Sinaloa">Sinaloa</option>
                <option value="Sonora">Sonora</option>
                <option value="Tabasco">Tabasco</option>
                <option value="Tamaulipas">Tamaulipas</option>
                <option value="Tlaxcala">Tlaxcala</option>
                <option value="Veracruz">Veracruz</option>
                <option value="Yucatán">Yucatán</option>
                <option value="Zacatecas">Zacatecas</option>
            </select>

            <br><br>
            <input type="text" name="presupuestoTotal" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número valido con al menos 2 dígitos" required>
        </div>
    </div>
</div>
<br><br>
<button class="btn btn-morado" type="submit">Crear</button>

</form>
</center>

</body>
</html>