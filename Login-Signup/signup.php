<?php

session_start();
include('../Conexion/conexion.php');

if (isset($_POST['Nombre']) && isset($_POST['ApePaterno']) && isset($_POST['ApeMaterno']) && isset($_POST['Correo']) && isset($_POST['TipoUsuario'])&& isset($_POST['Contraseña'])) {
    function validar($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }   

    $Nombre = validar($_POST['Nombre']);
    $ApePaterno = validar($_POST['ApePaterno']);
    $ApeMaterno = validar($_POST['ApeMaterno']);
    $Correo = validar($_POST['Correo']);
    $TipoUsuario = validar($_POST['TipoUsuario']);
    $Contraseña = validar($_POST['Contraseña']);

    $Usuario = 

    $VerificarUsuario = "SELECT * FROM usuarios WHERE correo = '$Correo'";
    $queryVeriUsuario = $conexion->query($VerificarUsuario);

    if(mysqli_num_rows($queryVeriUsuario) > 0){
        header('location:signup.php?error="El usuario ya existe"');
    }else {
        $sqlIngresarUsuario = "INSERT INTO usuarios`(tipoUsuario, usuario, nombre, apellidoPaterno, apellidoMaterno, correo, contraseña, codigo, estatus, fechaRegistro) VALUES";
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="sty.css">
    <title>Sign Up</title>
    
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

    <center>
        <h1>Sign Up</h1>
        <p>Ya tienes cuenta? <a href="login.php">Log in</a></p>
    </center>  
    <br>

    <form action="signup.php" method="POST" class="container">

        <div class="container text-center">
        <div class="row">
            <div class="col">
            <label>Nombre</label>
            </div>
            <div class="col">
            <label>Apellido Paterno</label>
            </div>
            <div class="col">
            <label>Apellido Materno</label>
            </div>
        </div>
        </div>

        <div class="container text-center">
        <div class="row">
            <div class="col">
            <input type="text" name="Nombre" placeholder="Nombre(s)" required>
            </div>
            <div class="col">
            <input type="text" name="ApePaterno" placeholder="Apellido Paterno" required>
            </div>
            <div class="col">
            <input type="text" name="ApeMaterno" placeholder="Apellido Materno" required>
            </div>
        </div>
        </div>
        
        <br>    
        
        <div class="container text-center">
        <div class="row">
            <div class="col">
            <label>Correo Electronico</label>
            </div>
            <div class="col">
            <label>Tipo de usuario</label>
            </div>
        </div>   

        <div class="container text-center">
        <div class="row">
            <div class="col">
            <input type="email" name="Correo" placeholder="Correo electronico" required>
            </div>
            <div class="col">
            <select name="TipoUsuario"  required>
                <option value=""></option>
                <option value="Novia o Novio">Novia o Novio</option>
                <option value="Ayudante de boda">Ayudante de boda</option>
                <option value="Proveedor">Proveedor</option>
            </select>
            </div>
        </div>      

        <label>Contraseña</label><br>
        <input type="password" name="Contraseña" placeholder="Contraseña" required>
        
        <br><br>

            <button class="btn btn-dark" type="submit">Crear cuenta</button>
    </form>
    
</body>
</html>
