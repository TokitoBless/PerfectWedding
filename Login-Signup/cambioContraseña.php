<?php

if (isset($_POST['nuevaContraseña']) && isset($_POST['confirmarContraseña']) ) {
  $Usuario = $_POST['nuevaContraseña'];
  $Contraseña = $_POST['confirmarContraseña'];
  echo $Usuario . "  " . $Contraseña;
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
    <h1>Cambiar contraseña</h1>
<br>
<form action="cambioContraseña.php" method="post">
      <label>Nueva contraseña</label>
      <br>
      <input type="passsword" name="nuevaContraseña" placeholder="Nueva" required>
      <br><br>
      <label>Confirmar contraseña</label>
      <br>
      <input type="password" name="confirmarContraseña" placeholder="Confirmar" required>
<br><br><br>
      <button class="btn btn-dark" type="submit">Confirmar</button>
    </form>
  </center>  
</body>
</html>