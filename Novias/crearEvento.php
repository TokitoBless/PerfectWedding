<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('../Conexion/conexion.php');
$eventoExistente = 0;

// Obtiene el ID del header desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Redirigir si no hay ID del servicio
    header('Location: crearEvento.php?error="No se proporcionó ID del usuario"');
    exit();
}

if (isset($_POST['fechaBoda']) && isset($_POST['estado']) && isset($_POST['presupuestoTotal'])){
    //checar si tiene otro evento creado

    $sqlVerificarBoda = "SELECT * FROM bodas WHERE usuario = '$id'";
    $queryVeriBoda = $Conexion->query($sqlVerificarBoda);

    if (mysqli_num_rows($queryVeriBoda) > 0) {
        //ya tiene un evento
        $eventoExistente = 1;
    }else{

        //Es su primer evento
        $fechaBoda = $_POST['fechaBoda'];
        $estado = $_POST['estado'];
        $presupuesto = $_POST['presupuestoTotal'];
        
        $sqlRevisarFecha = "SELECT fechaRegistro FROM usuarios WHERE id = '$id'";
        $queryRevisarFecha = $Conexion->query($sqlRevisarFecha);
        $row = mysqli_fetch_row($queryRevisarFecha);
        $fechaRegistro = $row[0];
        $fechaLimite = new DateTime($fechaRegistro);
        $fechaLimite = $fechaLimite->modify('+1 month');
        $fechaLimite = $fechaLimite->format('Y-m-d'); 

        if($fechaBoda > $fechaLimite){
            //la fecha ingresada el mayor de un mes
            $sqlIngresarBoda = "INSERT INTO bodas(usuario, fechaBoda, estado, presupuestoTotal) VALUE ('$id', '$fechaBoda', '$estado', '$presupuesto')";
            $queryIngresarBoda = $Conexion->query($sqlIngresarBoda);
            if($queryIngresarBoda){
                $sqlIdBoda = "SELECT MAX(idEvento) FROM bodas WHERE usuario = '$id'";
                $queryIdBoda = $Conexion->query($sqlIdBoda);
                $row = mysqli_fetch_row($queryIdBoda);
                $idBoda = $row[0];
                header('location:seleccionElementos.php?success=Evento creado&idUsuario=' . $id . '&idBoda='.$idBoda.'');
                exit();
            }
        }else{
            echo '<script language="javascript">alert("La fecha de la boda debe de ser de un mes despues de su registro.\r\nPuede ser despues de la fecha '.$fechaLimite.'");</script>';
        }

    }
}

// Verificar si se ha solicitado la eliminación
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $eventoExistente = 0;
    // Eliminar el evento de la tabla bodas
    $sqlEliminarBoda = "DELETE FROM bodas WHERE usuario = '$id'";
    $sqlEliminarElementosBoda = "DELETE FROM elementosBoda WHERE usuario = '$id'";

    if ($Conexion->query($sqlEliminarBoda) === TRUE) {
        if ($Conexion->query($sqlEliminarElementosBoda) === TRUE) {
            echo 'Se ha eliminado correctamente';
            exit;
        }
    }
}
// Verificar si se ha solicitado editar el evento existente
if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    // Checar si hay un evento y obtener el ID del evento
    $sqlVerificarBoda = "SELECT idEvento FROM bodas WHERE usuario = '$id'";
    $queryVeriBoda = $Conexion->query($sqlVerificarBoda);

    $row = mysqli_fetch_row($queryVeriBoda);
    $idBoda = $row[0];

    // Checar si hay elementos seleccionados en la boda
    $sqlVerificarElementos = "SELECT * FROM elementosboda WHERE evento = '$idBoda'";
    $queryVerificarElementos = $Conexion->query($sqlVerificarElementos);

    if (mysqli_num_rows($queryVerificarElementos) == 0) {
        // No hay elementos seleccionados, redirigir a seleccionElementos
        echo json_encode(['redirect' => 'seleccionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda]);
        exit();

    } else {
        // Hay elementos, ahora checar si tienen descripción
        $sqlVerificarDescripcion = "SELECT * FROM elementosboda WHERE evento = '$idBoda' AND (descripcion IS NULL OR descripcion = '')";
        $queryVerificarDescripcion = $Conexion->query($sqlVerificarDescripcion);

        if (mysqli_num_rows($queryVerificarDescripcion) > 0) {
            // Hay elementos sin descripción, redirigir a descripcionElementos
            echo json_encode(['redirect' => 'descripcionElementos.php?idUsuario=' . $id . '&idBoda=' . $idBoda]);
            exit();

        } else {
            // Todos los elementos tienen descripción, redirigir a tableroGeneral
            echo json_encode(['redirect' => 'panelGeneral.php?idUsuario=' . $id . '&idBoda=' . $idBoda]);
            exit();
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Nuevo evento</title>
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

<center><br><br><br><br>
<form action="crearEvento.php?id=<?php echo $id; ?>"  method="POST">
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            <label>Fecha de la boda</label>
            <br><br>
            <label>Estado</label>
            <br><br>
            <label>Presupuesto total</label>
        </div>

        <div class="col-4">
            <input type="date" name="fechaBoda" required>
            <br><br>
            <select id="estado" name="estado" required>
                <option value="">Selecciona un estado</option>
                <option value="Aguascalientes">Aguascalientes</option>
                <option value="Baja California">Baja California</option>
                <option value="Baja California Sur">Baja California Sur</option>
                <option value="Campeche">Campeche</option>
                <option value="Chiapas">Chiapas</option>
                <option value="Chihuahua">Chihuahua</option>
                <option value="Ciudad de México">Ciudad de México</option>
                <option value="Coahuila">Coahuila</option>
                <option value="Colima">Colima</option>
                <option value="Durango">Durango</option>
                <option value="Guanajuato">Guanajuato</option>
                <option value="Guerrero">Guerrero</option>
                <option value="Hidalgo">Hidalgo</option>
                <option value="Jalisco">Jalisco</option>
                <option value="México">México</option>
                <option value="Michoacán">Michoacán</option>
                <option value="Morelos">Morelos</option>
                <option value="Nayarit">Nayarit</option>
                <option value="Nuevo León">Nuevo León</option>
                <option value="Oaxaca">Oaxaca</option>
                <option value="Puebla">Puebla</option>
                <option value="Querétaro">Querétaro</option>
                <option value="Quintana Roo">Quintana Roo</option>
                <option value="San Luis Potosí">San Luis Potosí</option>
                <option value="Sinaloa">Sinaloa</option>
                <option value="Sonora">Sonora</option>
                <option value="Tabasco">Tabasco</option>
                <option value="Tamaulipas">Tamaulipas</option>
                <option value="Tlaxcala">Tlaxcala</option>
                <option value="Veracruz">Veracruz</option>
                <option value="Yucatán">Yucatán</option>
                <option value="Zacatecas">Zacatecas</option>
            </select>

            <br><br>
            <input type="text" name="presupuestoTotal" pattern="^(?!0{2,})\d{2,}$" title="El precio debe ser un número valido con al menos 2 dígitos" required>
        </div>
    </div>
</div>
<br><br>
<button class="btn btn-morado" type="submit">Crear</button>

</form>
<br><br>
<?php
if ($eventoExistente == 1) {
?>
    <label>Ya tienes un evento creado ¿Quieres eliminarlo y crear otro?</label>
    <button type="button" class="btn btn-rosa" onclick="confirmarEliminacion()">Eliminar evento</button>
    <br><br>
    <label>¿O quieres seguir con el mismo evento?</label>
    <button type="button" class="btn btn-lila" onclick="editarEvento()" >Evento ya creado</button>

    <script>

    function confirmarEliminacion() {
        // Mostrar confirmación antes de eliminar el evento
        if (confirm("¿Estás seguro de que deseas eliminar el evento?\r\nDespues de eliminarlo ya no podra recuperar los datos")) {
            // Crear un objeto FormData para enviar la petición AJAX
            const formData = new FormData();
            formData.append('accion', 'eliminar');
            
            // Hacer la petición AJAX para eliminar el evento
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // Mandar la solicitud al mismo archivo PHP
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Mostrar el resultado de la operación
                    alert(xhr.responseText);
                }
            };
            xhr.send(formData); // Enviar el formulario con la acción "eliminar"
        }
    }

    function editarEvento() {
        const formData = new FormData();
        formData.append('accion', 'editar');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); // Enviar al mismo archivo PHP
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        // Intenta interpretar la respuesta como JSON
                        const response = JSON.parse(xhr.responseText);

                        // Si la respuesta contiene la instrucción de redirección
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            // Manejar otras respuestas o mensajes
                            alert(xhr.responseText);
                        }
                    } catch (e) {
                        // Si la respuesta no es JSON o no puede ser interpretada
                        console.error("Error interpretando la respuesta:", e);
                        alert("Hubo un error al procesar la respuesta.");
                    }
                } else {
                    console.error("Error en la solicitud AJAX:", xhr.status, xhr.statusText);
                    alert("Hubo un error al procesar la solicitud.");
                }
            }
        };
        xhr.send(formData);
    }


    </script>
<?php
}
?>

</center>

</body>
</html>