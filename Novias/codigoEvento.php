<?php
include_once('../Conexion/conexion.php');

// Obtiene el ID del header desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if(isset($_POST['usuarioBoda'])){
  $usuarioIngresado = $_POST['usuarioBoda'];//usuario ingresado

  $sqlVerificarUsuario = "SELECT * FROM usuarios WHERE usuario = '$usuarioIngresado'";
  $queryVeriUsuario = $Conexion->query($sqlVerificarUsuario);

  if (mysqli_num_rows($queryVeriUsuario) > 0) {
    // El usuario existe en la tabla usaurios
    $fila = $queryVeriUsuario->fetch_assoc();
    $idUsuario = $fila['id'];//sacar el id del usuario que ingreso

    $sqlVerificarBoda = "SELECT * FROM bodas WHERE usuario = '$idUsuario'";
    $queryVeriBoda = $Conexion->query($sqlVerificarBoda);

    if (mysqli_num_rows($queryVeriBoda) > 0) {
      //El usuario esta en la tabla da bodas
      $filaBoda = $queryVeriBoda->fetch_assoc();
      $idBoda = $filaBoda['idEvento'];//sacar el id de la boda

      echo '<script language="javascript">alert("Suerte ayudando!!!");window.location.href = "panelGeneral.php?idUsuario=' . $id . '&idBoda=' . $idBoda . '";</script>';
    }else {
      // No hay ninguna boda con ese usuario como encargado
      echo '<script language="javascript">alert("No hay ninguna boda con ese usuario como encargado");</script>';
    }


  }else {
    // El usuario no existe
    echo '<script language="javascript">alert("El usuario ingresado no existe");</script>';
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Codigo de evento</title>
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

    <div class="card" style="width: 30rem;">
    <div class="card-body">
        <br>
        <h4 class="card-title">Codigo del evento</h3>
        <h6 class="card-subtitle mb-2 text-body-secondary">Para ser parte de esta boda, ingresa el usuario de la persona que creo el evento</h6>
        <br><br>
        <form action="codigoEvento.php?id=<?php echo $id; ?>" method="POST">
            <input type="text" name="usuarioBoda">
            <br><br><br>
            <button class="btn btn-rosa"  type="submit">Ayudar en esta boda</button>
            <br><br>
        </form>
        <a class="btn btn-morado " href="crearEvento.php?id=<?php echo $id; ?>" role="button">Crear tu propio evento</a>
        <br><br>
    </div>
    </div>

</center>

</body>
</html>