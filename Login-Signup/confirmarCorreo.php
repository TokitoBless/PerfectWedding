<?php

include_once('../Conexion/conexion.php');

if (isset($_GET['id'])) {
  $ID = $_GET['id'];
  $codigo = null;
  if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
  }
  
  
  $sqlInfo = "SELECT codigo, fechaRegistro FROM usuarios WHERE id = $ID";
  $queryInfo = $Conexion->query($sqlInfo);

  $row = mysqli_fetch_row($queryInfo);
  $codigoConfirmacion = $row[0];
  $fecha = $row[1];

  $fechaLimite = new DateTime($fecha);
  $fechaLimite->modify('+5 minutes');
  $fechaActual = new DateTime();

  if ($fechaLimite > $fechaActual){
    if ($codigo == $codigoConfirmacion) {
      $sqlUpdate = "UPDATE usuarios SET estatus = 'Activo' WHERE id = '$ID'";
      $queryUpdate = $Conexion->query($sqlUpdate);
      header('location:login.php?success="Bienvenido"');
      exit();
    }else {
      echo '<script language="javascript">alert("El codigo es incorrecto");</script>';
    }
  }else {
    echo '<script language="javascript">alert("El tiempo del codigo ya expiro");</script>';
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
    <link rel="stylesheet" type="text/css" href="sty.css">
    <title>Confirmar correo</title>
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
<br>

<div class="container">
    <center>
    <form action="confirmarCorreo.php" method="GET">
        <h3>Codigo de confirmacion</h3>
        <p>El codigo fue enviado a su correo electronico</p>
        <br>
        <input type="number" name="codigo" placeholder="Codigo de confirmacion" required style="width: 300px; padding: 5px; ">
        <input type="hidden" name="id" id="id" value="<?php echo $ID?>">
        <br><br><br>
        <div class="d-grid gap-2 col-6 mx-auto">
        <button class="btn btn-dark" type="submit">Confirmar</button>
        <button class="btn btn-info" type="button">Reenviar codigo</button>
    </div>
    </form>
    
    </center>
</div>
</body>
</html>