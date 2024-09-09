<?php

session_start();
include_once('../Conexion/conexion.php');
$estatusInactivo = 0;

function verificarCredenciales($Usuario, $Contraseña) {
    global $Conexion; 
    global $estatusInactivo;

    // 1. Verificar si existe el usuario
    $sqlVerificarUsuario = "SELECT * FROM usuarios WHERE usuario = '$Usuario'";
    $queryVeriUsuario = $Conexion->query($sqlVerificarUsuario);

    if (mysqli_num_rows($queryVeriUsuario) > 0) {
        // El usuario existe
        $fila = $queryVeriUsuario->fetch_assoc();
        $contraseñaHash = $fila['contraseña'];
        $estatusUsuario = $fila['estatus'];
        $tipoUsuario = $fila['tipoUsuario'];
        $id = $fila['id']; // Asumiendo que 'id' es el identificador del usuario

        // 2. Verificar si el usuario está en la base de datos de login (intentos fallidos)
        $sqlVerificarLogin = "SELECT intentos, tiempoBloqueado FROM login WHERE usuario = '$Usuario'";
        $queryVeriLogin = $Conexion->query($sqlVerificarLogin);
        $rowLogin = mysqli_fetch_assoc($queryVeriLogin);

        if ($rowLogin) {
            // 3. El usuario está en la tabla 'login', verificar si está bloqueado
            $intentosUsuario = $rowLogin['intentos'];
            $tiempoBloqueado = $rowLogin['tiempoBloqueado'];

            $tiempoActual = new DateTime();
            $tiempoActualStr = $tiempoActual->format('H:i:s');

            if ($tiempoBloqueado !== '00:00:00.00000' && $tiempoActualStr > $tiempoBloqueado) {
                // 4. Desbloquear usuario si el tiempo de bloqueo ha pasado
                $sqlDesbloquearUsuario = "DELETE FROM login WHERE usuario = '$Usuario'";
                $Conexion->query($sqlDesbloquearUsuario);
                verificarCredenciales($Usuario, $Contraseña); // Volver a verificar las credenciales
            } elseif ($tiempoBloqueado == '00:00:00.00000') {
                // 5. Verificar si la contraseña es correcta
                if (password_verify($Contraseña, $contraseñaHash)) {
                    // Contraseña correcta, verificar estatus del usuario
                    if ($estatusUsuario == 'Inactivo') {
                        echo '<script language="javascript">alert("Falta la confirmación de correo para ingresar al sistema");</script>';
                        $estatusInactivo = 1;
                    } else {
                        // Usuario activo, redirigir según el tipo de usuario
                        if($tipoUsuario == "Ayudante de boda") {
                            header('location:infoAyudante.php?success="Bienvenido"');
                            exit();
                        } elseif ($tipoUsuario == "Proveedor") {
                            header('location:../Proveedor/infoCuenta.php?success="Bienvenido proveedor&id='. $id .'"');
                            exit();
                        } else {
                            header('location:../Novias/codigoEvento.php?success="Bienvenido novia/novio"');
                            exit();
                        }
                    }
                } else {
                    // Contraseña incorrecta, incrementar intentos
                    $intentosUsuario++;
                    if ($intentosUsuario <= 3) {
                        $sqlActualizarIntentos = "UPDATE login SET intentos = '$intentosUsuario' WHERE usuario = '$Usuario'";
                        $Conexion->query($sqlActualizarIntentos);
                        if ($intentosUsuario == 3) {
                            // Bloquear usuario por 5 minutos
                            $tiempoBloqueo = new DateTime();
                            $tiempoBloqueo->modify('+5 minutes');
                            $tiempoBloqueoStr = $tiempoBloqueo->format('H:i:s');
                            $sqlBloquearUsuario = "UPDATE login SET tiempoBloqueado = '$tiempoBloqueoStr' WHERE usuario = '$Usuario'";
                            $Conexion->query($sqlBloquearUsuario);
                            echo '<script language="javascript">alert("Usuario bloqueado por 5 minutos.");</script>';
                        } else {
                            echo '<script language="javascript">alert("Contraseña incorrecta, intentos restantes: '.(3 - $intentosUsuario).'");</script>';
                        }
                    }
                }
            } else {
                // Usuario está bloqueado
                echo '<script language="javascript">alert("Su usuario está bloqueado por 5 minutos.");</script>';
            }
        } else {
            // 3. El usuario no está en la tabla 'login', registrar primer intento fallido
            if (password_verify($Contraseña, $contraseñaHash)) {
                // Contraseña correcta, verificar estatus del usuario
                if ($estatusUsuario == 'Inactivo') {
                    echo '<script language="javascript">alert("Falta la confirmación de correo para ingresar al sistema");</script>';
                    $estatusInactivo = 1;
                } else {
                    // Usuario activo, redirigir según el tipo de usuario
                    if($tipoUsuario == "Ayudante de boda") {
                        header('location:infoAyudante.php?success="Bienvenido&id='. $id .'""');
                        exit();
                    } elseif ($tipoUsuario == "Proveedor") {
                        header('location:../Proveedor/infoCuenta.php?success="Bienvenido proveedor&id='. $id .'"');
                        exit();
                    } else {
                        header('location:../Novias/codigoEvento.php?success="Bienvenido novia/novio&id='. $id .'""');
                        exit();
                    }
                }
            } else {
                // Contraseña incorrecta, registrar en 'login' con 1 intento
                $sqlRegistrarIntento = "INSERT INTO login (usuario, intentos, tiempoBloqueado) VALUES ('$Usuario', '1', '00:00:00.00000')";
                $Conexion->query($sqlRegistrarIntento);
                echo '<script language="javascript">alert("Contraseña incorrecta, tiene 2 intentos más.");</script>';
            }
        }
    } else {
        // El usuario no existe
        echo '<script language="javascript">alert("El usuario ingresado no existe");</script>';
    }
}

if (isset($_POST['Usuario']) && isset($_POST['Contraseña'])) {
    $Usuario = $_POST['Usuario'];
    $Contraseña = $_POST['Contraseña'];
    verificarCredenciales($Usuario, $Contraseña); 
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
      <form action="confirmarCorreo.php?id=<?php echo $id; ?>" method="post">
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