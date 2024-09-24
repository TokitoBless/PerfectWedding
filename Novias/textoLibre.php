<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $id = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    
} else {
    header('Location: textoLibre.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
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
    <title>Nuevo evento</title>
    
</head>
<body>

<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="title_nav">
            <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
            <span>Perfect Wedding</span>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $id; ?>">Calendario</a>
                <a class="nav-item nav-link" href="conversaciones.php?id=<?php echo $id; ?>">Tabla kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Tableros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Tablero general</a></li>
                            <li><a class="dropdown-item" href="#">Tableros favoritos</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $id; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br><h3>Describe tu boda</h3>
<form action="textoLibre.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>" method="post">

    <div style="text-align: right; margin-top: 20px; padding-right: 10px;">
        <button type="submit" class="btn btn-lila">Guardar</button>
        <a type="button" class="btn btn-rosa" href="descripcionElementos.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>">Formulario</a>
    </div>
    <br>
    <div style="padding: 30px">

        <div class="form-floating">
        <textarea class="form-control" name="descripcion" placeholder="Describe tu boda" id="floatingTextarea2" style="height: 290px" required></textarea>
        <label for="floatingTextarea2">Como te imaginas tu boda</label>
        </div>

    </div>
    
</form>
</body>
</html>