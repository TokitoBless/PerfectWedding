<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Informacion de cuenta</title>
</head>
<body>
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <div class="title_nav">
      <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
      <span>Perfect Wedding</span>
    </div>
  </div>
</nav>
<br>
<h3>Informacion de cuenta</h3>
<h6>Nombre del representante</h6>
<form class="form-container" action = "infoCuenta.php" method = "POST">
  <div class="row">
      <div class="column">
          <label for="apellido_paterno">Apellido Paterno:</label>
          <input type="text" id="apellido_paterno" name="apellido_paterno" required>
      </div>
      <div class="column">
          <label for="apellido_materno">Apellido Materno:</label>
          <input type="text" id="apellido_materno" name="apellido_materno" required>
      </div>
      <div class="column">
          <label>Nombre:</label>
          <input type="text" name="nombre" required>
      </div>
  </div>

  <div class="row">
      <div class="column-full">
          <label>Nombre Empresa:</label>
          <input type="text" name="nombreEmpresa" required>
      </div>
  </div>

  <div class="row">
      <div class="column">
          <label>Ciudad:</label>
          <input type="text" name="ciudad" required>
      </div>
      <div class="column">
          <label>Estado:</label>
          <select id="estado" name="estado" required>
              <option value="">Selecciona un estado</option>
              <!-- Añade aquí las opciones de los estados -->
          </select>
      </div>
  </div>

  <div class="row">
      <div class="column">
          <label>Teléfono:</label>
          <input type="text" name="telefono" required>
      </div>
      <div class="column">
          <label >Sitio Web:</label>
          <input type="url" name="sitio_web">
      </div>
  </div>
  
  <button type="submit" class="btn btn-primary btn-submit">Enviar</button>

</form>
</body>
</html>
