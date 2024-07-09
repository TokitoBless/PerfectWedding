<?php
session_start();
include_once('../Conexion/conexion.php');

if (isset($_POST['apellidoPaterno']) && isset($_POST['apellidoMaterno']) && isset($_POST['nombre']) && isset($_POST['nombreEmpresa']) && isset($_POST['ciudad']) && isset($_POST['estado']) && isset($_POST['telefono'])) {
    function validar($data){
        $data = trim($data);
        $data = stripcslashes($data);
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
    $Calificacion = 0;

    $sqlVerificarEmpresa = "SELECT * FROM proveedores WHERE nombreEmpresa = '$NombreEmpresa'";
    $queryVeriEmpresa = $Conexion->query($sqlVerificarEmpresa);

    if(mysqli_num_rows($queryVeriEmpresa) > 0){
        header('location:infoCuenta.php?error="El proveedor ya existe"');
        exit();
    }else{
        $sqlIngresarProveedor = "INSERT INTO proveedores (apellidoPaterno, apellidoMaterno, nombre, nombreEmpresa, ciudad, estado, telefono, sitioWeb, calificacion) VALUES ('$ApellidoPaterno', '$ApellidoMaterno', '$Nombre', '$NombreEmpresa', '$Ciudad', '$Estado', '$Telefono', '$SitioWeb', '$Calificacion')";
        $queryIngresarProv = $Conexion->query($sqlIngresarProveedor);

        $sqlId = "SELECT MAX(id) AS max_id FROM proveedores";
        $queryId = $Conexion->query($sqlId);
        
        $row = mysqli_fetch_row($queryId);
        $max_id = $row[0];

        if ($queryIngresarProv) {
            header('location:panelServicios.php?success=Proveedor creado&id=' . $max_id . '');
            exit();
        }else {
            header('location:infoCuenta.php?success="Hubo un error en la creacion"');
            exit();
        }
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
    <title>Informacion de cuenta</title>
</head>
<body>
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <div class="title_nav">
      <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
      <span>Perfect Wedding</span>
    </div>
  </div>
</nav>
<br>
<h3>Informacion de cuenta</h3>
<h6>Nombre del representante</h6>
<form class="form-container" action = "infoCuenta.php" method = "POST">
  <div class="row">
      <div class="column">
          <label for="apellido_paterno">Apellido Paterno:</label>
          <input type="text" name="apellidoPaterno" pattern="[A-Za-z]+" title="Solo se permiten letras" required>
      </div>
      <div class="column">
          <label for="apellido_materno">Apellido Materno:</label>
          <input type="text" name="apellidoMaterno" pattern="[A-Za-z]+" title="Solo se permiten letras" required>
      </div>
      <div class="column">
          <label>Nombre:</label>
          <input type="text" name="nombre" pattern="[A-Za-z]+" title="Solo se permiten letras" required>
      </div>
  </div>

  <div class="row">
      <div class="column-full">
          <label>Nombre Empresa:</label>
          <input type="text" name="nombreEmpresa" required>
      </div>
  </div>

  <div class="row">
      <div class="column">
          <label>Ciudad:</label>
          <input type="text" name="ciudad" pattern="[A-Za-z]+" title="Solo se permiten letras" required>
      </div>
      <div class="column">
          <label>Estado:</label>
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

      </div>
  </div>

  <div class="row">
      <div class="column">
          <label>Teléfono:</label>
          <input type="tel" name="telefono" pattern="[356][0-9]{9}" title="El número de celular debe comenzar con 3, 5 o 6 y tener 10 dígitos en total" required>
      </div>
      <div class="column">
          <label >Sitio Web:</label>
          <input type="url" name="sitioWeb">
      </div>
  </div>
  
  <button type="submit" class="btn btn-primary btn-submit">Enviar</button>

</form>
</body>
</html>
