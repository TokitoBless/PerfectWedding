<?php
include_once('../Conexion/conexion.php');

$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
$idServicio = isset($_GET['idServicio']) ? $_GET['idServicio'] : '';
$idBoda = isset($_GET['idBoda']) ? $_GET['idBoda'] : ''; 

$sqlUsuario = "SELECT usuario FROM usuarios where id = ?";
$stmt = $Conexion->prepare($sqlUsuario);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$usuario = $row['usuario'];

if(isset($_POST['comentario'])&&isset($_POST['calificacion'])){
    
    $comentario = $_POST['comentario'];
    $calificacion = $_POST['calificacion'];
    $fecha = date('Y-m-d H:i:s');

    $sql = "INSERT INTO resenas (idBoda, idUsuario, idServicio, resena, calificacion, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $Conexion->prepare($sql);
    $stmt->bind_param("iiisis", $idBoda, $idUsuario, $idServicio, $comentario, $calificacion, $fecha);
    $stmt->execute();
    header('location:listaResenas.php?idUsuario=' . base64_encode($idUsuario ). '&idBoda='. base64_encode($idBoda).'');
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
    <link rel="stylesheet" type="text/css" href="stylesResena.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escribir reseñas</title>
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
<h2>Reseña para el servicio</h2>
<br>
<div class ="cont-body">
    <div class="form-container">
        <form id="reviewForm" action="escribirResena.php?idBoda=<?php echo $idBoda?>&idServicio=<?php echo $idServicio?>&idUsuario=<?php echo $idUsuario?>" method="POST">

            <label for="nombre">Nombre del usuario:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario); ?>" readonly>

            <label for="comentario">Comentario:</label>
            <textarea id="comentario" name="comentario" rows="4" required></textarea>

            <label for="calificacion">Calificación:</label>
            <div class="rating">
                <input type="radio" name="calificacion" value="5" id="5-stars"><label for="5-stars" title="5 estrellas">&#9733;</label>
                <input type="radio" name="calificacion" value="4" id="4-stars"><label for="4-stars" title="4 estrellas">&#9733;</label>
                <input type="radio" name="calificacion" value="3" id="3-stars"><label for="3-stars" title="3 estrellas">&#9733;</label>
                <input type="radio" name="calificacion" value="2" id="2-stars"><label for="2-stars" title="2 estrellas">&#9733;</label>
                <input type="radio" name="calificacion" value="1" id="1-star"><label for="1-star" title="1 estrella">&#9733;</label>
            </div>


            <button type="submit">Enviar Reseña</button>
        </form>
    </div>
</div>
</body>
</html>