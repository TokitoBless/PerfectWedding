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
        $idSinCodificar = $fila['id'];
        $id = base64_encode($idSinCodificar);

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
                            $sqlAyudante = "SELECT * FROM ayudantes WHERE idUsuario = $idSinCodificar";
                            $queryAyudante = $Conexion->query($sqlAyudante);
                            if (mysqli_num_rows($queryAyudante) > 0) {//El ayudante ya esta en una boda
                                $row = $queryAyudante->fetch_assoc();
                                $idBodaSinEncriptar = $row['idEvento'];
                                $idBoda = base64_encode($idBodaSinEncriptar);
                                header("Location: ../Novias/panelGeneral.php?idUsuario=$id&idBoda=$idBoda");
                                exit();
                            }else{//No esta en ninguna boda
                                header('location:infoAyudante.php?success="Bienvenido&id='. $id .'"');
                                exit();
                            }
                            
                        } elseif ($tipoUsuario == "Proveedor") {
                            header('location:../Proveedor/infoCuenta.php?success=Bienvenido proveedor&id='. $id .'');
                            exit();
                        } else {
                            // Checar si hay un evento y obtener el ID del evento
                            $sqlVerificarBoda = "SELECT idEvento FROM bodas WHERE usuario = '$idSinCodificar'";
                            $queryVeriBoda = $Conexion->query($sqlVerificarBoda);
                            if (mysqli_num_rows($queryVeriBoda) > 0) { //si esta dentro de una boda
                                $row = mysqli_fetch_row($queryVeriBoda);
                                $idBodaSinEncriptar = $row[0];
                                $idBoda = base64_encode($idBodaSinEncriptar);
                                // Checar si hay elementos seleccionados en la boda
                                $sqlVerificarElementos = "SELECT * FROM elementosboda WHERE evento = '$idBodaSinEncriptar'";
                                $queryVerificarElementos = $Conexion->query($sqlVerificarElementos);
    
                                if (mysqli_num_rows($queryVerificarElementos) == 0) {
                                    // No hay elementos seleccionados, redirigir a seleccionElementos
                                    header('location:../Novias/seleccionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                    exit();
    
                                } else {
                                    // Hay elementos, ahora checar si tienen descripción
                                    $sqlVerificarDescripcion = "SELECT * FROM elementosboda WHERE evento = '$idBodaSinEncriptar' AND (descripcion IS NULL OR descripcion = '')";
                                    $queryVerificarDescripcion = $Conexion->query($sqlVerificarDescripcion);
    
                                    if (mysqli_num_rows($queryVerificarDescripcion) > 0) {
                                        // Hay elementos sin descripción, redirigir a descripcionElementos
                                        header('location:../Novias/descripcionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                        exit();
    
                                    } else {
                                        // Todos los elementos tienen descripción, redirigir a tableroGeneral
                                        header('location:../Novias/panelGeneral.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                        exit();
                                    }
                                }
                            }else{
                                header('location:../Novias/codigoEvento.php?success="Bienvenido novia/novio"');
                                exit();
                            }
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
                        $sqlAyudante = "SELECT * FROM ayudantes WHERE idUsuario = $idSinCodificar";
                        $queryAyudante = $Conexion->query($sqlAyudante);
                        if (mysqli_num_rows($queryAyudante) > 0) {//El ayudante ya esta en una boda
                            $row = $queryAyudante->fetch_assoc();
                            $idBodaSinEncriptar = $row['idEvento'];
                            $idBoda = base64_encode($idBodaSinEncriptar);
                            header("Location: ../Novias/panelGeneral.php?idUsuario=$id&idBoda=$idBoda");
                            exit();
                        }else{//No esta en ninguna boda
                            header('location:infoAyudante.php?success="Bienvenido&id='. $id .'"');
                            exit();
                        }
                        
                    } elseif ($tipoUsuario == "Proveedor") {
                        $sqlProveedor = "SELECT * FROM proveedores WHERE idUsuario = $idSinCodificar";
                        $queryProveedor = $Conexion->query($sqlProveedor);
                        if (mysqli_num_rows($queryProveedor) > 0) {//El proveedor ya tiene cuenta
                            $row = $queryProveedor->fetch_assoc();
                            $idProveedorSinEncriptar = $row['id'];
                            $idProveedor = base64_encode($idProveedorSinEncriptar);
                            header("Location: ../Proveedor/panelServicios.php?id=$idProveedor");
                            exit();
                        }else{//No esta en ninguna cuenta
                            header('location:../Proveedor/infoCuenta.php?success=Bienvenido proveedor&id='. $id .'');
                            exit();
                        }
                        
                    } else {
                        // Checar si hay un evento y obtener el ID del evento
                        $sqlVerificarBoda = "SELECT idEvento FROM bodas WHERE usuario = '$idSinCodificar'";
                        $queryVeriBoda = $Conexion->query($sqlVerificarBoda);
                        if (mysqli_num_rows($queryVeriBoda) > 0) { //si esta dentro de una boda
                            $row = mysqli_fetch_row($queryVeriBoda);
                            $idBodaSinEncriptar = $row[0];
                            $idBoda = base64_encode($idBodaSinEncriptar);
                            // Checar si hay elementos seleccionados en la boda
                            $sqlVerificarElementos = "SELECT * FROM elementosboda WHERE evento = '$idBodaSinEncriptar'";
                            $queryVerificarElementos = $Conexion->query($sqlVerificarElementos);

                            if (mysqli_num_rows($queryVerificarElementos) == 0) {
                                // No hay elementos seleccionados, redirigir a seleccionElementos
                                header('location:../Novias/seleccionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                exit();

                            } else {
                                // Hay elementos, ahora checar si tienen descripción
                                $sqlVerificarDescripcion = "SELECT * FROM elementosboda WHERE evento = '$idBodaSinEncriptar' AND (descripcion IS NULL OR descripcion = '')";
                                $queryVerificarDescripcion = $Conexion->query($sqlVerificarDescripcion);

                                if (mysqli_num_rows($queryVerificarDescripcion) > 0) {
                                    // Hay elementos sin descripción, redirigir a descripcionElementos
                                    header('location:../Novias/descripcionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                    exit();

                                } else {
                                    // Todos los elementos tienen descripción, redirigir a tableroGeneral
                                    header('location:../Novias/panelGeneral.php?idUsuario=' . $id . '&idBoda=' . $idBoda .'');
                                    exit();
                                }
                            }
                        }else{
                            header('location:../Novias/codigoEvento.php?success="Bienvenido novia/novio"');
                            exit();
                        }
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