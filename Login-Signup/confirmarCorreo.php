<?php

include_once('../Conexion/conexion.php');

if (isset($_GET['id'])) {
  $ID = $_GET['id'];
  $codigo = null;
  if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $sqlInfo = "SELECT codigo, fechaRegistro, tipoUsuario FROM usuarios WHERE id = $ID";
    $queryInfo = $Conexion->query($sqlInfo);

    $row = mysqli_fetch_row($queryInfo);
    $codigoConfirmacion = $row[0];
    $fecha = $row[1];
    $tipoUsuario = $row[2];
    $fechaLimite = new DateTime($fecha);
    $fechaLimite->modify('+5 minutes');
    $fechaActual = new DateTime();

    if ($fechaLimite > $fechaActual){
      if ($codigo == $codigoConfirmacion) {
        $sqlUpdate = "UPDATE usuarios SET estatus = 'Activo' WHERE id = '$ID'";
        $queryUpdate = $Conexion->query($sqlUpdate);
        if($tipoUsuario == "Ayudante de boda")
        {
          header('location:infoAyudante.php?success="Bienvenido"');
          exit();
        }elseif ($tipoUsuario == "Proveedor") {
          header('location:../Proveedor/infoCuenta.php?success="Bienvenido proveedor"');
          exit();
        }else {
          header('location:../Novias/codigoEvento.php?success="Bienvenido novia/novio"');
          exit();
        }
        
      }else {
        echo '<script language="javascript">alert("El codigo es incorrecto");</script>';
      }
    }else {
      echo '<script language="javascript">alert("El tiempo del codigo ya expiro");</script>';
    }
  }
  if(isset($_GET['reenviar'])){
    $CodigoConfirmacion = rand(10000, 99999);
    $fecha = new DateTime();
    $FechaReenvio = $fecha->format('Y-m-d H:i:s'); 
    
    $sqlUpdate = "UPDATE usuarios SET codigo = '$CodigoConfirmacion', fechaRegistro = '$FechaReenvio' WHERE id = '$ID'";
    $queryUpdate = $Conexion->query($sqlUpdate);

    $nombreEmpresa = 'PerfectWedding';
    $destino = 'dianapdz09@gmail.com'; //correo del cliente
    $asunto = 'Reenvio de codigo de confirmacion';
  
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
    $headers .= "FROM: $nombreEmpresa <$destino>\r\n";
    mail($destino,$asunto,$contenido,$headers);
    echo '<script language="javascript">alert("El codigo fue enviado a su correo por favor de verificarlo");</script>';
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
        </div>
    </form>

    <br>

    <form action="confirmarCorreo.php" method="GET">
        <input type="hidden" name="id" id="id" value="<?php echo $ID?>">
        <input type="hidden" name="reenviar" value="1">
        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-info btn-morado" type="submit">Reenviar código</button>
        </div>
    </form>
    
    </center>
</div>
</body>
</html>