<?php
include_once('../Conexion/conexion.php');

if (isset($_POST['UsuarioNovia'])) {
    $UsuarioNovia = $_POST['UsuarioNovia'];
    
    $sqlUsuario = "SELECT * FROM usuarios WHERE usuario = '$UsuarioNovia' AND tipoUsuario = 'Novia'";
    $queryUsuario = $Conexion->query($sqlUsuario);

    if(mysqli_num_rows($queryUsuario) > 0){
        echo '<script language="javascript">alert("Bienvenido");</script>';
    }else {
        echo '<script language="javascript">alert("El usuario es incorrecto");</script>';
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
    <title>Informacion Ayudante</title>
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
<br><br><br>
<center>
<div class="card w-75 mb-3">
  <div class="card-body">
    <h5 class="card-title">Ingrese el usuario de la novia</h5>
    <p class="card-text">La novia de la boda a la que desea ayudar le debe de proporsionar su nombre de usuario</p>
    <form action="infoAyudante.php" method="post">
        <input type="text" name = "UsuarioNovia" placeholder="Usuario de novia" required style="width: 400px; padding: 5px; ">
        <br><br>
        <button class="btn btn-dark" type="submit">Ingresar</button>
    </form>
  </div>
</div>
</center>

</body>
</html>