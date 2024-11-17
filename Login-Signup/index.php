<?php
include_once('../Conexion/conexion.php');

  $sqlVerificarFecha = "SELECT * FROM bodas";
  $queryVeriFecha = $Conexion->query($sqlVerificarFecha);

  while ($row = $queryVeriFecha->fetch_assoc()) {
    
    $fechaBoda = new DateTime($row['fechaBoda']);
    $fechaActual = new DateTime();
    $fechaBoda->modify('+7 days');

    if ($fechaBoda < $fechaActual) {
      $idBoda = $row['idEvento'];
      $idUsuario = $row['usuario'];
      
      $fechaC = new DateTime();
      $fechaCreacion = $fechaC->format('Y-m-d'); 

      $detallesNotificacion = "Gracias por utilizar Perfect Wedding, ¡¡Queremos saber tu opinion!!\nPor favor ingresa a tu correo para acceder al link";
      //La fecha ya paso
      $sqlGuardarNotificacion = "INSERT INTO notificaciones(idEvento, idUsuario, notificacion, fecha, detalles) VALUE ('$idBoda', '$idUsuario', 'Nuevo evento', '$fechaCreacion', '$detallesNotificacion' )";    
      $queryGuardarNotificacion = $Conexion->query($sqlGuardarNotificacion);

      $sqlUsuario= "SELECT usuario, correo from usuarios where id = '$idUsuario'";
      $queryUsuario = $Conexion->query($sqlUsuario);
      $row = $queryUsuario->fetch_assoc();
      $usuario = $row['usuario'];
      $correo = $row['correo'];

      //Envio del correo
      $nombreEmpresa = 'PerfectWedding';
      $destino = 'dianapdz09@gmail.com'; //correo del cliente
      $asunto = 'Deja tu reseña sobre los servicios seleccionados';
  
      $contenido = '
          <html> 
              <body> 
                  <h2>¡Hola '.$usuario.'! </h2>
                  <p> 
                      Gracias por utilizar Perfect Wedding, ¡¡Queremos saber tu opinion!! <br>
                      Ingresa al siguiente link para dirigirte a la lista de reseñas <br>
                      http://localhost/PROYECTO/Novias/listaResenas.php?idBoda='. base64_encode($idBoda) .'&idUsuario='. base64_encode($idUsuario) .'
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
    }
}

$sqlVerificarUsuarios = "SELECT * FROM usuarios";
$queryVeriUsuarios = $Conexion->query($sqlVerificarUsuarios);

while ($row = $queryVeriUsuarios->fetch_assoc()) {
  $fechaCreacion = new DateTime($row['fechaRegistro']);
  $fechaCreacion->modify('+7 days');
  $fechaActual = new DateTime();

  $usuario = $row['usuario'];
  $estatus = $row['estatus'];

  if($estatus == 'Inactivo'){
    if ($fechaActual >= $fechaCreacion) {//Hoy es mayor a una semana del registro
      $sqlEliminarInactivo = "DELETE FROM usuarios WHERE usuario = '$usuario'";    
      $queryEliminarInactivo = $Conexion->query($sqlEliminarInactivo);
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
    <link rel="stylesheet" type="text/css" href="sty.css">
    <title>Pagina de inicio</title>
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

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
  <a class="btn btn-info btn-morado" href="signup.php" role="button">Sign up</a>
  <a class="btn btn-info btn-morado" href="login.php" role="button">Log in</a>
</div>
<br>
<div class="container">
<div id="carouselExampleIndicators" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../Imagenes/decoCarrusel1.jpeg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="../Imagenes/decoCarrusel2.jpeg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="../Imagenes/decoCarrusel3.jpeg" class="d-block w-100" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

</div>

</body>
</html>