<?php
include_once('../Conexion/conexion.php');

if (isset($_POST['Correo'])) {
    $Correo = $_POST['Correo'];
    $sqlVerificarUsuario = "SELECT * FROM usuarios WHERE correo = '$Correo'";
    $queryVeriUsuario = $Conexion->query($sqlVerificarUsuario);

    if(mysqli_num_rows($queryVeriUsuario) > 0){
        $sqlId = "SELECT id FROM usuarios WHERE correo = '$Correo'";
        $queryId = $Conexion->query($sqlId);
        
        $row = mysqli_fetch_row($queryId);
        $ID = $row[0];

        $nombreEmpresa = 'PerfectWedding';
        $destino = 'dianapdz09@gmail.com'; //correo del cliente
        $asunto = 'Cambio de contraseña';
      
        $contenido = '
            <html> 
                <body> 
                    <h2>Para confirmar tu solicitud de cambio de contraseña, haz clic en el siguiente enlace</h2>
                    <p> 
                        http://localhost/PROYECTO/Login-Signup/cambioContrase%C3%B1a.php?id='. base64_encode($ID) .'
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
        echo '<script language="javascript">alert("Se ha enviado el enlace para cambiar la contraseña al correo registrado");window.location.href = "login.php";</script>';
    }else {
        echo '<script language="javascript">alert("El correo ingresado no existe");</script>';
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
    <title>Cambio de contraseña</title>
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
    <h1>Cambiar contraseña</h1>
<br>
<form action="correoContraseña.php" method="post">
      <label>Ingrese el correo electronico registrado</label>
      <br>
      <input type="email" name="Correo" placeholder="Correo electronico"  pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Ingrese un correo electrónico válido" required>
      <input type="hidden" name="id" id="id" value="<?php echo $ID?>">
<br><br><br>
      <button class="btn btn-dark" type="submit">Confirmar</button>
    </form>
  </center>  
</body>
</html>