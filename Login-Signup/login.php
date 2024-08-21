<?php

session_start();
include_once('../Conexion/conexion.php');
$estatusInactivo = 0;
if (isset($_POST['Usuario']) && isset($_POST['Contraseña'])) {
  $Usuario = $_POST['Usuario'];
  $Contraseña = $_POST['Contraseña'];
  $sqlVerificarCuenta = "SELECT * FROM usuarios WHERE usuario = '$Usuario' AND contraseña = '$Contraseña'";
  $queryVeriCuenta = $Conexion->query($sqlVerificarCuenta);

  if(mysqli_num_rows($queryVeriCuenta) > 0){//Existe la cuenta
    header('location:index.php?success="Bienvenido al merequetengue"');
    exit();
  }else{
    $sqlVerificarUsuario = "SELECT * FROM usuarios WHERE usuario = '$Usuario'";
    $queryVeriUsuario = $Conexion->query($sqlVerificarUsuario);

    if(mysqli_num_rows($queryVeriUsuario) > 0){//el usuario es correcto
      $fila = $queryVeriUsuario->fetch_assoc();
      $contraseñaHash = $fila['contraseña'];
      $estatusUsuario = $fila['estatus'];

      if(password_verify($Contraseña, $contraseñaHash)){//la contraseña es correcta
        
        if($estatusUsuario == 'Inactivo'){ //todavia no confirma su correo
          echo '<script language="javascript">alert("Falta la confirmacion de correo para ingresar al sistema");</script>';
          $estatusInactivo = 1;
        }else{//estatus activo
          header('location:index.php?success="Bienvenido al merequetengue "');
          exit();
        }
        
      }else{
        //Verificar si el usuario tiene intentos 
        $sqlVerificarIntentos = "SELECT intentos, tiempoBloqueado	 FROM login WHERE usuario = '$Usuario'";
        $queryVerifIntentos = $Conexion->query($sqlVerificarIntentos);
        $row = mysqli_fetch_row($queryVerifIntentos);
        
        if(mysqli_num_rows($queryVerifIntentos) > 0){//si esta dentro de la base de datos
          $intentosUsuario = $row[0];
          $tiempoBloqueado =$row[1];
          
          $tiempoActual = new DateTime();
          $tiempoActual = $tiempoActual->format('H:i:s'); 

          if ($tiempoBloqueado !== '00:00:00.00000' && $tiempoActual > $tiempoBloqueado) {
            // El tiempo actual es mayor al tiempo bloqueado
            echo "Estas desbloqueado";
            $sqlUsuarioDesbloqueado = "DELETE FROM login WHERE usuario = '$Usuario';";
            $queryUsuarioDesbloqueado = $Conexion->query($sqlUsuarioDesbloqueado);

          } elseif ($tiempoBloqueado == '00:00:00.00000') {
            // No hay tiempo bloqueado, así que puedes proceder 
            //No se a bloquedo sigue con los intentos
            $intentosUsuario ++;

            if($intentosUsuario <= 3){//todavia tiene intentos
              $sqlSegundoIntento = "UPDATE login SET intentos = '$intentosUsuario' WHERE usuario = '$Usuario';";
              $querySegundoIntento = $Conexion->query($sqlSegundoIntento);
  
              if($intentosUsuario == 2){
                echo '<script language="javascript">alert("La contraseña es incorrecta, por favor de volver a ingresarla. Tiene solo 1 intento más");</script>';

              }else if($intentosUsuario == 3){
                $ultimoIntento = new DateTime();//hora del bloqueo de usuario mas 5 minutos
                $ultimoIntento->modify('+5 minutes'); // hora cuuando se debe de liberar el bloqueo del usuario
                $ultimoIntento = $ultimoIntento->format('H:i:s');
          
                $sqlUsuarioBloqueado = "UPDATE login SET tiempoBloqueado = '$ultimoIntento' WHERE usuario = '$Usuario';";
                $queryUsuarioBloqueado = $Conexion->query($sqlUsuarioBloqueado);
                echo '<script language="javascript">alert("La contraseña es incorrecta, su usuario ha sido bloqueado por 5 minutos.");</script>';
              }
            }
          }else{
            echo '<script language="javascript">alert("Su usuario esta bloqueado por 5 minutos.");</script>';
          }
        }else{//no ha sido ingresada
          $sqlPrimerIntento = "INSERT INTO login (usuario, intentos, tiempoBloqueado) VALUES ('$Usuario', '1', '00:00:00.00000');";
          $queryPrimerIntento = $Conexion->query($sqlPrimerIntento);

          if ($queryPrimerIntento) { //se le ingresa a la bd y se le notifica su primer intento fallido
            echo '<script language="javascript">alert("La contraseña es incorrecta, por favor de volver a ingresarla. Tiene solo 2 intentos más");</script>';
          }
        }

      }
    }else{
      echo '<script language="javascript">alert("El usuario ingresado no existe");</script>';
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
    <title>Log in</title>
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
    <h1>Log in</h1>
    <p>No tienes cuenta? <a href="signup.php">Sign up</a></p>
    <?php
    if ($estatusInactivo == 1) {
      ?>
      <form action="confirmarCorreo.php" method="post">
        <label>Para activar tu correo</label><br>
        <button class="btn btn-light" type="submit">Da clik aqui</button>
      </form>
      <?php
    }
    ?>
<br>
    <form action="login.php" method="POST">

      <label>Usuario</label>
      <br>
      <input type="text" name="Usuario" placeholder="Usuario" required>
      <br><br>
      <label>Contraseña</label>
      <br>
      <input type="password" name="Contraseña" placeholder="Contraseña" required>
      <p class="text-body-secondary">No recuerdas tu contraseña?<br><a href="correoContraseña.php">Cambiar contraseña</a></p>
<br><br>
      <button class="btn btn-dark" type="submit">Log in</button>
    </form>
  </center>  
</body>
</html>