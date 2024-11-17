<?php
include_once('../Conexion/conexion.php');
$idSinEncriptar = $_GET['id'];
$id = base64_decode($idSinEncriptar);

if (isset($_POST['UsuarioNovia'])) {
    $UsuarioNovia = $_POST['UsuarioNovia'];
    
    //agarrar idUsuario
    $sqlIdUsuario = "SELECT id FROM usuarios WHERE usuario = '$UsuarioNovia'";
    $queryIdUsuario = $Conexion->query($sqlIdUsuario);//id novia
    if(mysqli_num_rows($queryIdUsuario) > 0)
    {
      $row = mysqli_fetch_row($queryIdUsuario);
      $idUsuario = $row[0];

      //Verificar si esta en la boda
      $sqlUsuario = "SELECT * FROM bodas WHERE usuario = '$idUsuario'";
      $queryUsuario = $Conexion->query($sqlUsuario);

      if(mysqli_num_rows($queryUsuario) > 0){
        $row = mysqli_fetch_row($queryUsuario);
        $idEventoSinEncriptar = $row[0];
        $idEvento = base64_encode($idEventoSinEncriptar);
        $sqlIngresarAyudante = "INSERT INTO ayudantes (idEvento, idUsuario) VALUES ('$idEventoSinEncriptar', '$id')";
        $queryIngresarAyudante = $Conexion->query($sqlIngresarAyudante);
        echo '<script language="javascript">alert("Bienvenido");</script>';
        header("Location: ../Novias/panelGeneral.php?idUsuario=$id&idBoda=$idEvento");
        exit();
      }else {
        echo '<script language="javascript">alert("El usuario es incorrecto");</script>';
      }
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
    <p class="card-text">La novia o el novio que creo la boda a la que desea ayudar le debe de proporsionar su nombre de usuario</p>
    <form action="infoAyudante.php?id=<?php echo base64_encode($id)?>" method="post">
        <input type="text" name = "UsuarioNovia" placeholder="Usuario de novia/o" required style="width: 400px; padding: 5px; ">
        <br><br>
        <button class="btn btn-dark" type="submit">Ingresar</button>
    </form>
  </div>
</div>
</center>

</body>
</html>