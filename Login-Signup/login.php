<?php

if (isset($_POST['Usuario']) && isset($_POST['Contraseña']) ) {
  $Usuario = $_POST['Usuario'];
  $Contraseña = $_POST['Contraseña'];
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
    <h1>Log in</h1>
    <p>No tienes cuenta? <a href="signup.php">Sign up</a></p>
<br>
    <form action="login.php" method="POST">

      <label>Usuario</label>
      <br>
      <input type="text" name="Usuario" placeholder="Usuario" required>
      <br><br>
      <label>Contraseña</label>
      <br>
      <input type="password" name="Contraseña" placeholder="Contraseña" required>
<br><br><br>
      <button class="btn btn-dark" type="submit">Log in</button>
    </form>
  </center>  
</body>
</html>