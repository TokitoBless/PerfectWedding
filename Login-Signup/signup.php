<?php

session_start();
include_once('../Conexion/conexion.php');


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

    $Usuario = $Nombre . $ApePaterno . $ApeMaterno[0];
    $Estatus = "Inactivo";

    $CodigoConfirmacion = rand(10000, 99999);
    $fecha = new DateTime();
    $FechaRegistro = $fecha->format('Y-m-d H:i:s'); 

    $sqlVerificarUsuario = "SELECT * FROM usuarios WHERE correo = '$Correo'";
    $queryVeriUsuario = $Conexion->query($sqlVerificarUsuario);

    if(mysqli_num_rows($queryVeriUsuario) > 0){
        header('location:signup.php?error="El usuario ya existe"');
        exit();
    }else {
        $sqlIngresarUsuario = "INSERT INTO usuarios (tipoUsuario, usuario, nombre, apellidoPaterno, apellidoMaterno, correo, contraseña, codigo, estatus, fechaRegistro) VALUES ('$TipoUsuario', '$Usuario', '$Nombre', '$ApePaterno', '$ApeMaterno', '$Correo', '$Contraseña', '$CodigoConfirmacion', '$Estatus', '$FechaRegistro')";
        $queryIngresarUsu = $Conexion->query($sqlIngresarUsuario);

        $sqlId = "SELECT MAX(id) AS max_id FROM usuarios";
        $queryId = $Conexion->query($sqlId);
        
        $row = mysqli_fetch_row($queryId);
        $max_id = $row[0];

        $nombreEmpresa = 'PerfectWedding';
        $destino = 'dianapdz09@gmail.com'; //correo del cliente
        $asunto = 'Codigo de confirmacion';
      
        $contenido = '
            <html> 
                <body> 
                    <h2>Este es su codigo de confirmacion para validar su correo electronico </h2>
                    <p> 
                        '.$CodigoConfirmacion.' 
                    </p> 
                </body>
            </html>
        ';
        //para el envío en formato HTML 
        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-type: text/html; charset=UTF8\r\n"; 

        //dirección del remitente
        $headers .= "FROM: $nombreEmpresa <$Correo>\r\n";
        mail($destino,$asunto,$contenido,$headers);
        
        if ($queryIngresarUsu) {
            header('location:confirmarCorreo.php?success=Usuario creado&id=' . $max_id . '');
            exit();
        }else {
            header('location:signup.php?success="Hubo un error en la creacion"');
            exit();
        }
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
            <input type="text" name="Nombre" placeholder="Nombre(s)"  pattern="[A-Za-z]+" title="Solo se permiten letras" required>
            </div>
            <div class="col">
            <input type="text" name="ApePaterno" placeholder="Apellido Paterno"  pattern="[A-Za-z]+" title="Solo se permiten letras" required>
            </div>
            <div class="col">
            <input type="text" name="ApeMaterno" placeholder="Apellido Materno"  pattern="[A-Za-z]+" title="Solo se permiten letras" required>
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
            <input type="email" name="Correo" placeholder="Correo electronico"  pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Ingrese un correo electrónico válido" required>
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
        <input type="password" name="Contraseña" placeholder="Contraseña" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"  title="La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una letra minúscula, un número y un carácter especial." required>
        
        <br><br>

            <button class="btn btn-dark" type="submit">Crear cuenta</button>
    </form>
    
</body>
</html>
