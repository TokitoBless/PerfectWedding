<?php

include_once('../Conexion/conexion.php');

if (isset($_GET['max_id'])) {
  $Id = $_GET['max_id'];
  $sqlInfo = "SELECT correo, codigo, fechaRegistro FROM usuarios WHERE id = $Id";
  $queryInfo = $Conexion->query($sqlInfo);
  
  $row = mysqli_fetch_row($queryInfo);
  $Correo = $row[0];
  $Codigo = $row[1];
  $Fecha = $row[2];
  echo c
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
    <form action="confirmarCorreo.php" method="POST">
        <h3>Codigo de confirmacion</h3>
        <p>El codigo fue enviado a su correo electronico</p>
        <br>
        <input type="number" name="codigo" placeholder="Codigo de confirmacion" required style="width: 300px; padding: 5px; ">
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