<?php
include_once('../Conexion/conexion.php');

if(isset($_GET['id'])) {
$id =  base64_decode($_GET['id']);
if (isset($_POST['nuevaContraseña']) && isset($_POST['confirmarContraseña']) ) {
  $Nueva = $_POST['nuevaContraseña'];
  $Confirmar = $_POST['confirmarContraseña'];
  if($Nueva == $Confirmar){
    $sqlCambio = "UPDATE usuarios SET contraseña = '$Nueva' WHERE id = '$id'";
    $queryCambio = $Conexion->query($sqlCambio);
    if ($queryCambio) {
      echo '<script language="javascript">alert("Se realizo el cambio de contraseña con exito?contra= '. $sqlCambio.'");window.location.href = "login.php";</script>';
    }else{

    }
  }else{
    echo '<script language="javascript">alert("Las contraseñas no coinciden, por favor de intentarlo de nuevo");</script>';
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
<form action="cambioContraseña.php?id=<?php echo base64_encode($id); ?> " method="post">
      <label>Nueva contraseña</label>
      <br>
      <input type="password" name="nuevaContraseña" placeholder="Nueva" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"  title="La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una letra minúscula, un número y un carácter especial." required>
      <br><br>
      <label>Confirmar contraseña</label>
      <br>
      <input type="password" name="confirmarContraseña" placeholder="Confirmar" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"  title="La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una letra minúscula, un número y un carácter especial." required>
<br><br><br>
      <button class="btn btn-dark" type="submit">Confirmar</button>
    </form>
  </center>  
</body>
</html>